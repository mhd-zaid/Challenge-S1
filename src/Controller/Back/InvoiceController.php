<?php

namespace App\Controller\Back;

use App\Entity\Invoice;
use App\Repository\InvoiceRepository;
use App\Repository\EstimateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Estimate;
use App\Repository\InvoicePrestationRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Core\Security;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/invoice')]
class InvoiceController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    #[Route('/', name: 'app_invoice_index', methods: ['GET'])]
    public function index(InvoiceRepository $invoiceRepository, Security $security, Request $request, PaginatorInterface $paginator): Response
    {
        if($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MECHANIC') || $this->isGranted('ROLE_ACCOUNTANT')){
            $invoices = $invoiceRepository->findAll();
        }else{
            $invoices = $invoiceRepository->findBy(['customer' => $security->getUser()->getId()]);
        }
        $invoicesPagination = $paginator->paginate(
            $invoices, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        return $this->render('back/invoice/index.html.twig', [
            'invoices' => $invoices,
            'invoicesPagination' => $invoicesPagination,
        ]);
    }

    #[Route('/{id}/success', name: 'app_invoice_success', methods: ['GET'])]
    #[Security('user == invoice.getCustomer() or is_granted("ROLE_MECHANIC") or is_granted("ROLE_ACCOUNTANT")')]
    public function success(Invoice $invoice,Pdf $pdf,InvoicePrestationRepository $invoicePrestationRepository): Response
    {
        $invoicePrestations = $invoicePrestationRepository->findBy(['invoice' => $invoice]);
        $total = $invoice->getTotal($invoicePrestationRepository);

        $html = $this->renderView('back/pdf/invoice.html.twig', [
            'invoice' => $invoice,
            'customer' => $invoice->getCustomer(),
            'invoicePrestations' => $invoicePrestations,
            'total' => $total
        ]);

        $pdfResponse = new PdfResponse(
            $pdf->getOutputFromHtml($html),
            'facture.pdf'
        );
        $pdfContent = $pdfResponse->getContent();
        $email = (new TemplatedEmail())
        ->from("zaidmouhamad@gmail.com")
        ->to($invoice->getCustomer()->getEmail())
        ->subject('Votre Facture
        ')
        ->htmlTemplate('back/email/invoiceEmail.html.twig')
        ->context([
            'customer' => $invoice->getCustomer(),
        ])
        ->attach($pdfContent, 'file.pdf');
        $this->mailer->send($email);
        
        return $this->render('back/invoice/success.html.twig', [
            'invoice' => $invoice
        ]);
    }

    #[Route('/{id}/download', name: 'app_invoice_download', methods: ['GET'])]
    #[Security('user == invoice.getCustomer() or is_granted("ROLE_MECHANIC") or is_granted("ROLE_ACCOUNTANT")')]
    public function download(Invoice $invoice,Pdf $pdf,InvoicePrestationRepository $invoicePrestationRepository): Response
    {
   
        $total = $invoice->getTotal($invoicePrestationRepository);
        $invoicePrestations = $invoicePrestationRepository->findBy(['invoice' => $invoice]);
        dump($invoicePrestations);
        $html = $this->renderView('back/pdf/invoice.html.twig', [
            'invoice' => $invoice,
            'customer' => $invoice->getCustomer(),
            'invoicePrestations' => $invoicePrestations,
            'total' => $total
        ]);
        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            'facture.pdf'
        );
    }

    #[Route('/{id}/{uuid}', name: 'app_invoice_update', methods: ['GET'])]
    #[Security('is_granted("ROLE_MECHANIC")')]
    public function update(Request $request, $uuid, Estimate $estimate, InvoiceRepository $invoiceRepository, EstimateRepository $estimateRepository): Response
    {
        if($estimate->getUuidSuccessPayment() !== $uuid){
            dump($estimate->getUuidSuccessPayment());
            dump($uuid);
            die;
            throw new NotFoundHttpException('Page not found');
        }
        $estimate->setStatus('PAID');
        $estimateRepository->save($estimate, true);
        $invoice = $estimate->getInvoice();

        $invoice->setStatus('PAID');
        $invoiceRepository->save($invoice, true);
        return $this->redirectToRoute('back_app_invoice_success', ['id' => $invoice->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/show', name: 'app_invoice_show', methods: ['GET'])]
    public function show(Invoice $invoice,InvoicePrestationRepository $invoicePrestationRepository): Response
    {
        $total = $invoice->getTotal($invoicePrestationRepository);
        $invoicePrestations = $invoicePrestationRepository->findBy(['invoice' => $invoice]);

        return $this->render('back/invoice/show.html.twig', [
            'invoice' => $invoice,
            'customer' => $invoice->getCustomer(),
            'invoicePrestations' => $invoicePrestations,
            'total' => $total
        ]);
    }

    // #[Route('/{id}/edit', name: 'app_invoice_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Invoice $invoice, InvoiceRepository $invoiceRepository): Response
    // {
    //     $form = $this->createForm(InvoiceType::class, $invoice);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $invoiceRepository->save($invoice, true);

    //         return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('invoice/edit.html.twig', [
    //         'invoice' => $invoice,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_invoice_delete', methods: ['POST'])]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function delete(Request $request, Invoice $invoice, InvoiceRepository $invoiceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invoice->getId(), $request->request->get('_token'))) {
            $invoiceRepository->remove($invoice, true);
        }

        return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
    }
}
