<?php

namespace App\Controller\Back;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use App\Repository\InvoiceRepository;
use App\Repository\EstimateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Estimate;
use Symfony\Component\Security\Core\Security;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/invoice')]
class InvoiceController extends AbstractController
{
    #[Route('/', name: 'app_invoice_index', methods: ['GET'])]
    public function index(InvoiceRepository $invoiceRepository, Security $security,Request $request,PaginatorInterface $paginator): Response
    {
        if($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MECHANIC') || $this->isGranted('ROLE_ACCOUNTANT')){
            $invoices = $invoiceRepository->findAll();
        }else{
            $invoices = $invoiceRepository->findBy(['client' => $security->getUser()->getId()]);
        }

        $pagination = $paginator->paginate(
            $invoices, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('back/invoice/index.html.twig', [
            'invoices' => $invoices,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_update', methods: ['GET'])]
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
    public function download(Invoice $invoice): Response
    {
        return $this->render('back/invoice/download.html.twig', []);
    }

    // #[Route('/new', name: 'app_invoice_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, InvoiceRepository $invoiceRepository): Response
    // {
    //     $invoice = new Invoice();
    //     $form = $this->createForm(InvoiceType::class, $invoice);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $invoiceRepository->save($invoice, true);

    //         return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('invoice/new.html.twig', [
    //         'invoice' => $invoice,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_invoice_show', methods: ['GET'])]
    public function show(Invoice $invoice): Response
    {
        return $this->render('back/invoice/show.html.twig', [
            'invoice' => $invoice,
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

    // #[Route('/{id}', name: 'app_invoice_delete', methods: ['POST'])]
    // public function delete(Request $request, Invoice $invoice, InvoiceRepository $invoiceRepository): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$invoice->getId(), $request->request->get('_token'))) {
    //         $invoiceRepository->remove($invoice, true);
    //     }

    //     return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
    // }
}
