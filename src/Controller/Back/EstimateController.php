<?php

namespace App\Controller\Back;

use App\Entity\Customer;
use App\Entity\Estimate;
use App\Form\EstimateType;
use App\Entity\Invoice;
use App\Entity\InvoicePrestation;
use App\Entity\EstimatePrestation;
use App\Repository\EstimateRepository;
use App\Repository\CustomerRepository;
use App\Repository\EstimatePrestationRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;

#[Route('/estimate')]
class EstimateController extends AdminController
{
    private $mailer;
    private $security;

    public function __construct(MailerInterface $mailer, Security $security)
    {
        $this->mailer = $mailer;
        $this->security = $security;
    }

    #[Route('/', name: 'app_estimate_index', methods: ['GET'])]
    public function index(EstimateRepository $estimateRepository, EstimatePrestationRepository $estimatePrestationRepository,Security $security, Request $request,  PaginatorInterface $paginator): Response
    {
        if($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MECHANIC') || $this->isGranted('ROLE_ACCOUNTANT')){
            $estimates = $estimateRepository->findAll();
        }else{
            $estimates = $estimateRepository->findBy(['customer' => $security->getUser()->getId()]);
        }

        $estimatesPagination = $paginator->paginate(
            $estimates, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        $estimatesTotal = $this->getEstimateTotal($estimates,$estimatePrestationRepository);

        return $this->render('back/estimate/index.html.twig', [
            'estimates' => $estimates,
            'estimatesPagination' => $estimatesPagination,
            'estimatesTotal' => $estimatesTotal
        ]);
    }

    #[Route('/new', name: 'app_estimate_new', methods: ['GET', 'POST'])]
    public function new(Pdf $pdf, Request $request, EntityManagerInterface $em): Response
    {
        $estimate = new Estimate();
        $invoice = new Invoice();
        $estimateRepository = $em->getRepository(Estimate::class);
        $invoiceRepository = $em->getRepository(Invoice::class);
        $customerRepository = $em->getRepository(Customer::class);
        $invoicePrestationRepository = $em->getRepository(InvoicePrestation::class);
        $estimatePrestationRepository = $em->getRepository(EstimatePrestation::class);

        $form = $this->createForm(EstimateType::class, $estimate);
        $form->handleRequest($request);
<<<<<<< HEAD
        
=======

>>>>>>> 1381fad (estimate controller set array prestations)
        if ($form->isSubmitted() && $form->isValid()) {
            $prestations = $form->get('estimatePrestations')->getData();
            $customer = $customerRepository->findOneBy([
                'email' => $form->get('email')->getData(),
                'isRegistered' => true
            ]);

                        
            $isCustomerExist = $customer ? true: false;

            if ($customer === null) {
                $id = $this->generateCustomerId($customerRepository);
                $customer = $this->createCustomer($form,$id, $customerRepository);
            }

            $invoice->setCustomer($customer);
            $invoice->setStatus('PENDING');
            $invoiceRepository->save($invoice, true);

            $estimate->setCustomer($customer);
            $estimate->setTitle($form->getData()->getTitle());
            $estimate->setValidityDate($form->get('validityDate')->getData());
            $estimate->setInvoice($invoice);
            $estimate->setStatus('PENDING');
            $estimateRepository->save($estimate, true);


            
            $prestations = $form->get('estimatePrestations')->getData();

            $emailCustomer = $form->get('email')->getData();
            $total = $estimate->getTotal($estimatePrestationRepository);
            $html = $this->renderView('back/pdf/estimate.html.twig', [
                'estimate' => $estimate,
                'customer' => $customer,
                'prestations' => $prestations,
                'total' => $total,
            ]);
            $pdfResponse = new PdfResponse(
                $pdf->getOutputFromHtml($html),
                'devis.pdf'
            );
            $pdfContent = $pdfResponse->getContent();
            if($isCustomerExist){
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
                    'token' => $customer->getValidationToken()
                ])
                ->attach($pdfContent, 'file.pdf');
                $this->mailer->send($email);
            }
            

            foreach($prestations as $prestation){
                $estimatePrestation = new EstimatePrestation();
                $estimatePrestation->setPrestation($prestation);
                $estimatePrestation->setEstimate($estimate);
                $estimatePrestationRepository->save($estimatePrestation, true);

                $invoicePrestation = new InvoicePrestation();
                $invoicePrestation->setPrestation($prestation);
                $invoicePrestation->setInvoice($invoice);
                $invoicePrestationRepository->save($invoicePrestation, true);

            }

           return $this->redirectToRoute('back_app_estimate_index', [], Response::HTTP_SEE_OTHER);
        }

        dump($form);
        return $this->renderForm('back/estimate/new.html.twig', [
            'estimate' => $estimate,
            'form' => $form,
        ]);
    }

    #[Route('/decline/{id}', name: 'app_estimate_decline', methods: ['GET'])]
    public function decline(Estimate $estimate, EntityManagerInterface $em): Response
    {
        //Reset les quantity au product et delete le devis et la facture avec leurs devisProduit et factureProduit correspondant
        $estimatePrestations = $em->getRepository(EstimatePrestation::class)->findBy(['estimate' => $estimate]);

        foreach($estimatePrestations as $estimatePrestation){
            $prestation = $estimatePrestation->getPrestation();
            foreach($prestation->getPrestationProducts() as $prestationProduct){
                $productUpdate = $prestationProduct->getProduct();
                $productUpdate->setQuantity($prestationProduct->getProduct()->getQuantity() + $prestationProduct->getQuantity());
                $em->getRepository(Product::class)->save($productUpdate, true);
            }
            
        }
        
        $estimate->setStatus('REFUSED');
        $em->getRepository(Estimate::class)->save($estimate, true);
        $invoice = $estimate->getInvoice();
        $invoice->setStatus('REFUSED');
        $em->getRepository(Invoice::class)->save($invoice, true);  
        return $this->render('back/estimate/index.html.twig', [
            'estimates' => $em->getRepository(Estimate::class)->findAll(),
            'isUser' => false
        ]);
    }

    #[Route('/{id}/download', name: 'app_estimate_download', methods: ['GET'])]
    public function download(Estimate $estimate, Pdf $pdf, EstimatePrestationRepository $estimatePrestationRepository): Response
    {
        $total = $estimate->getTotal($estimatePrestationRepository);
        $estimatePrestations = $estimatePrestationRepository->findBy(['estimate' => $estimate]);

        $prestations = [];

        foreach($estimatePrestations as $estimatePrestation){
            $prestations[] = $estimatePrestation->getPrestation();
        }
        $html = $this->renderView('back/pdf/estimate.html.twig', [
            'estimate' => $estimate,
            'customer' => $estimate->getCustomer(),
            'prestations' => $prestations,
            'total' => $total
        ]);
        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            'file.pdf'
        );
    }

    #[Route('/{id}', name: 'app_estimate_delete', methods: ['POST'])]
    public function delete(Request $request, Estimate $estimate, EstimateRepository $estimateRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$estimate->getId(), $request->request->get('_token'))) {
            $estimateRepository->remove($estimate, true);
        }

        return $this->redirectToRoute('app_estimate_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/show', name: 'app_estimate_show', methods: ['GET'])]
    public function show(Estimate $estimate,EstimatePrestationRepository $estimatePrestationRepository): Response
    {
        $total = $estimate->getTotal($estimatePrestationRepository);
        $estimatePrestations = $estimatePrestationRepository->findBy(['estimate' => $estimate]);

        return $this->render('back/estimate/show.html.twig', [
            'estimate' => $estimate,
            'customer' => $estimate->getCustomer(),
            'estimatePrestations' => $estimatePrestations,
            'total' => $total
        ]);
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
        $customer->setLastname($form->get('lastname')->getData());
        $customer->setFirstname($form->get('firstname')->getData());
        $customer->setEmail($form->get('email')->getData());
        $customer->setValidationToken(Uuid::v4()->__toString());        
    
        $customerRepository->save($customer,true);

        return $customer;
    }

    public function getEstimateTotal(Array $estimates,EstimatePrestationRepository $estimatePrestationRepository): array
    {
        $estimatesTotal = [];
        foreach ($estimates as $estimate) {
            $estimatesTotal[] = [
                'id' => $estimate->getId(),
                'total' => $estimate->getTotal($estimatePrestationRepository)

            ];
        }
        return $estimatesTotal;
    }
}
