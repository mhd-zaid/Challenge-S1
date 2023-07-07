<?php

namespace App\Controller\Back;

use App\Entity\Customer;
use App\Entity\Estimate;
use App\Form\EstimateType;
use App\Entity\Invoice;
use App\Entity\InvoicePrestation;
use App\Repository\EstimateRepository;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Sec;

#[Route('/estimate')]
class EstimateController extends AbstractController
{
    private $em;
    private $mailer;
    private $security;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer, Security $security)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->security = $security;
    }

    #[Route('/', name: 'app_estimate_index', methods: ['GET'])]
    #[Sec('is_granted("ROLE_CUSTOMER") and !is_granted("ROLE_ACCOUNTANT") or is_granted("ROLE_ADMIN")')]
    public function index(EstimateRepository $estimateRepository, Request $request): Response
    {
        if($this->isGranted('ROLE_ADMIN')){
            return $this->render('back/estimate/index.html.twig', [
                'estimates' => $estimateRepository->findAll(),
                'isUser' => false
            ]);
        }else{
            $estimates = $estimateRepository->findBy(['client' => $this->security->getUser()]);
            return $this->render('back/estimate/index.html.twig', [
                'estimates' => $estimates,
                'isUser' => true
            ]);
        }
    }

    #[Route('/new', name: 'app_estimate_new', methods: ['GET', 'POST'])]
    #[Sec('is_granted("ROLE_MECHANIC")')]
    public function new(Pdf $pdf, Request $request, EntityManagerInterface $em): Response
    {
        $estimate = new Estimate();
        $invoice = new Invoice();
        $estimateRepository = $em->getRepository(Estimate::class);
        $invoiceRepository = $em->getRepository(Invoice::class);
        $customerRepository = $em->getRepository(Customer::class);

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

            $invoice->setClient($customer);
            $invoice->setStatus('PENDING');
            $invoiceRepository->save($invoice, true);

            $estimate->setCustomer($customer);
            $estimate->setTitle($form->getData()->getTitle());
            $estimate->addPrestation($form->getData()->getPrestations());
            $estimate->setValidityDate($form->get('validityDate')->getData());
            $estimate->setInvoice($invoice);
            $estimate->setStatus('PENDING');
            $estimateRepository->save($estimate, true);

            
            $emailCustomer = $form->get('email')->getData();

            if($isCustomerExist){
            $html = $this->renderView('back/pdf/estimate.html.twig', [
                'estimate' => $estimate,
                'customer' => $customer,
                //'estimatePrestations' => $estimate->getEstimatePrestations(),
            ]);
            $pdfResponse = new PdfResponse(
                $pdf->getOutputFromHtml($html),
                'file.pdf'
            );
            $pdfContent = $pdfResponse->getContent();
            $email = (new TemplatedEmail())
            ->from("zaidmouhamad@gmail.com")
            ->to($emailCustomer)
            ->subject('Votre Devis
            ')
            ->htmlTemplate('back/email/devisEmail.html.twig')
            ->context([
                'customer' => $customer,
            ])
            ->attach($pdfContent, 'file.pdf');
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

            foreach($estimate->getestimatePrestations() as $value){
                dump($value);die;
                $invoicePrestation = new InvoicePrestation();
                $invoicePrestation->setPrestation($value);
                $invoicePrestation->setInvoice($invoice);
                $em->getRepository(InvoicePrestation::class)->save($invoicePrestation, true);
            }

           return $this->redirectToRoute('back_app_estimate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/estimate/new.html.twig', [
            'estimate' => $estimate,
            'form' => $form,
            'estimatePrestations' => $estimate->getEstimatePrestations(),
        ]);
    }

    // #[Route('/decline/{id}', name: 'app_estimate_decline', methods: ['GET'])]
    // #[Sec('user.getId() == estimate.getClient().getId() or is_granted("ROLE_MECHANIC")')]
    // public function decline(Estimate $estimate, EstimateRepository $estimateRepository, InvoiceRepository $invoiceRepository, EstimateProductRepository $estimateProductRepository, ProductRepository $productRepository, InvoiceProductRepository $invoiceProductRepository): Response
    // {
    //     //Reset les quantity au product et delete le devis et la facture avec leurs devisProduit et factureProduit correspondant
    //     $invoiceProduct = $invoiceProductRepository->findBy(['invoice' => $estimate->getInvoice()]);
    //     $estimateProduct = $estimateProductRepository->findBy(['estimate' => $estimate]);

    //     dump($invoiceProduct);
    //     foreach($estimateProduct as $product){
    //         $productUpdate = $product->getProduct();
    //         $productUpdate->setQuantity($product->getProduct()->getQuantity() + $product->getQuantity());
    //         $productRepository->save($productUpdate, true);
    //     }
    //     $estimate->setStatus('REFUSED');
    //     $estimateRepository->save($estimate, true);
    //     $invoice = $estimate->getInvoice();
    //     $invoice->setStatus('REFUSED');
    //     $invoiceRepository->save($invoice, true);  
    //     return $this->render('back/estimate/index.html.twig', [
    //         'estimates' => $estimateRepository->findAll(),
    //         'isUser' => false
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_estimate_download', methods: ['GET'])]
    // public function download(Estimate $estimate, Pdf $pdf, EstimateProductRepository $estimateProductRepository, ProductRepository $productRepository): Response
    // {
    //     $estimateProduct = $estimateProductRepository->findBy(['estimate' => $estimate]);
    //     $estimateData = [];
    //     $total = 0;
    //     foreach($estimateProduct as $product){
    //         $productData = $product->getProduct();
    //         $total += ((($product->getTotalTVA() / 100) * $product->getTotalHT()) + $product->getTotalHT()) * $product->getQuantity();
    //         $estimateData[] = [
    //             'product' => $productData,
    //             'quantity' => $product->getQuantity(),
    //         ];
    //     }
    //     $total+= $estimateProduct[0]->getWorkforce();
    //     $html = $this->renderView('back/pdf/estimate.html.twig', [
    //         'estimate' => $estimate,
    //         'customer' => $estimate->getClient(),
    //         'workforce' => $estimateProduct[0]->getWorkforce(),
    //         'products' => $estimateData,
    //         'total' => $total
    //     ]);
    //     return new PdfResponse(
    //         $pdf->getOutputFromHtml($html),
    //         'file.pdf'
    //     );
    // }

    #[Route('/{id}/edit', name: 'app_estimate_edit', methods: ['GET', 'POST'])]
    #[Sec('user.getId() == estimate.getClient().getId() or is_granted("ROLE_MECHANIC")')]
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
    #[Sec('is_granted("ROLE_MECHANIC")')]
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
