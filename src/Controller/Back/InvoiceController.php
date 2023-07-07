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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Route('/invoice')]
class InvoiceController extends AbstractController
{
    #[Route('/', name: 'app_invoice_index', methods: ['GET'])]
    #[Security('is_granted("ROLE_CUSTOMER") or is_granted("ROLE_ACCOUNTANT")')]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        return $this->render('back/invoice/index.html.twig', [
            'invoices' => $invoiceRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_update', methods: ['GET'])]
    #[Security('is_granted("ROLE_MECHANIC")')]
    public function update(Estimate $estimate, InvoiceRepository $invoiceRepository, EstimateRepository $estimateRepository): Response
    {
        $estimate->setStatus('PAID');
        $estimateRepository->save($estimate, true);
        $invoice = $estimate->getInvoice();

        $invoice->setStatus('PAID');
        $invoiceRepository->save($invoice, true);

        return $this->redirectToRoute('back_app_invoice_download', ['id' => $invoice->getId()]);
    }

    #[Route('/{id}/download', name: 'app_invoice_download', methods: ['GET'])]
    #[Security('user == invoice.getCustomer() or is_granted("ROLE_MECHANIC") or is_granted("ROLE_ACCOUNTANT")')]
    public function download(Invoice $invoice,Pdf $pdf,InvoicePrestationRepository $invoicePrestationRepository): Response
    {
        $total = $invoice->getTotal($invoicePrestationRepository);
        $invoicePrestations = $invoicePrestationRepository->findBy(['invoice' => $invoice]);
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
