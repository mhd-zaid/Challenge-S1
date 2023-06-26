<?php

namespace App\Controller\Back;

use App\Entity\Customer;
use App\Entity\Estimate;
use App\Form\EstimateType;
use App\Entity\Product;
use App\Entity\Invoice;
use App\Entity\EstimateProduct;
use App\Entity\InvoiceProduct;
use App\Repository\ProductRepository;
use App\Repository\EstimateRepository;
use App\Repository\InvoiceRepository;
use App\Repository\EstimateProductRepository;
use App\Repository\InvoiceProductRepository;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use App\Services\PdfGenerator;
use Mpdf;

#[Route('/estimate')]
class EstimateController extends AbstractController
{
    private $em;
    private $mailer;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    #[Route('/', name: 'app_estimate_index', methods: ['GET'])]
    public function index(EstimateRepository $estimateRepository): Response
    {
        return $this->render('back/estimate/index.html.twig', [
            'estimates' => $estimateRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_estimate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EstimateRepository $estimateRepository, InvoiceRepository $invoiceRepository, ProductRepository $productRepository, CustomerRepository $customerRepository, EstimateProductRepository $estimateProductRepository, InvoiceProductRepository $invoiceProductRepository, PdfGenerator $pdfGenerator): Response
    {
        $estimate = new Estimate();
        $invoice = new Invoice();

        $products = $productRepository->findAll();
        $form = $this->createForm(EstimateType::class, $estimate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $customer = $customerRepository->findOneBy([
                'email' => $form->get('email')->getData()
            ]);
            $isCustomerExist = $customer ? true: false;

            if ($customer === null) {
                $id = $this->generateCustomerId($customerRepository);
                $customer = $this->createCustomer($form,$id, $customerRepository);
            }
            $estimate->setClient($customer);
            $estimate->setTitle($form->getData()->getTitle());
            $estimate->setValidityDate($form->get('validity_date')->getData());
            $estimateRepository->save($estimate, true);

            $invoice->setClient($customer);
            $invoice->setStatus('PENDING');
            $invoiceRepository->save($invoice, true);
            //isCLientExised -> jenvoie mail d'inscripion + devis sinon juste devis 
            $emailCustomer = $form->get('email')->getData();
            if($isCustomerExist){
            $email = (new TemplatedEmail())
            ->from("zaidmouhamad@gmail.com")
            ->to($emailCustomer)
            ->subject('Confirm email')
            ->htmlTemplate('back/email/devisEmail.html.twig')
            ->context([
                'customer' => $customer,
            ]);
            $this->mailer->send($email);
            }else{
                $email = (new TemplatedEmail())
                ->from("zaidmouhamad@gmail.com")
                ->to($emailCustomer)
                ->subject('Confirm email')
                ->htmlTemplate('back/email/inscriptionEmail.html.twig')
                ->context([
                    'customer' => $customer,
                ]);
                $this->mailer->send($email);
            }
            $products = $form->get('productQuantities')->getData();
            foreach($products as $value){
                $product = $productRepository->find($value['product']->getId());
                $product->setQuantity($product->getQuantity() - $value['quantity']);
                $productRepository->save($product, true);

                $estimateProduct = new EstimateProduct();
                $estimateProduct->setEstimate($estimate);
                $estimateProduct->setProduct($product);
                $estimateProduct->setTotalHt($product->getTotalHt());
                $estimateProduct->setTotalTva($product->getTotalTva());
                $estimateProduct->setQuantity($value['quantity']);
                $estimateProduct->setWorkforce($form->get('workforce')->getData());
                $estimateProductRepository->save($estimateProduct, true);

                $total = (($product->getTotalTva() / 100) * $product->getTotalHt()) + $product->getTotalHt() + $form->get('workforce')->getData();

                $invoiceProduct = new InvoiceProduct();
                $invoiceProduct->setProduct($product);
                $invoiceProduct->setInvoice($invoice);
                $invoiceProduct->setTotal($total);
                $invoiceProduct->setQuantity($value['quantity']);
                $invoiceProductRepository->save($invoiceProduct, true);
            }

            return $this->redirectToRoute('back_app_estimate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/estimate/new.html.twig', [
            'estimate' => $estimate,
            'form' => $form,
            'products' => $products
        ]);
    }

    #[Route('/{id}', name: 'app_estimate_show', methods: ['GET'])]
    public function show(Estimate $estimate): Response
    {
        return $this->render('back/estimate/show.html.twig', [
            'estimate' => $estimate,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_estimate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Estimate $estimate, EstimateRepository $estimateRepository): Response
    {
        $form = $this->createForm(EstimateType::class, $estimate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $estimateRepository->save($estimate, true);

            return $this->redirectToRoute('app_estimate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/estimate/edit.html.twig', [
            'estimate' => $estimate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_estimate_delete', methods: ['POST'])]
    public function delete(Request $request, Estimate $estimate, EstimateRepository $estimateRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$estimate->getId(), $request->request->get('_token'))) {
            $estimateRepository->remove($estimate, true);
        }

        return $this->redirectToRoute('app_estimate_index', [], Response::HTTP_SEE_OTHER);
    }

    public function checkId(int $id, CustomerRepository $customerRepository): bool
    {
        $user = $customerRepository->find($id);
        dump($user);
        if($user === null){
            return false;
        }

        return true;
    }

    public function generateCustomerId(CustomerRepository $customerRepository): int
    {
        $id = 0;
        do {
            $id = random_int(10000, 20000);
        } while ($this->checkId($id, $customerRepository));
        return $id;
    }

    public function createCustomer(object $form,int $id, CustomerRepository $customerRepository): Customer
    {
        $customer = new Customer();
        $customer->setId($id);
        $customer->setValidationToken(Uuid::v4()->__toString());        
    
        $customerRepository->save($customer,true);

        return $customer;
    }
}
