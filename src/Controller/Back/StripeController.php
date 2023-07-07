<?php

namespace App\Controller\Back;

use App\Entity\Customer;
use App\Entity\Estimate;
use App\Form\EstimateType;
use App\Entity\Product;
use App\Entity\Invoice;
use App\Entity\InvoiceProduct;
use App\Repository\ProductRepository;
use App\Repository\EstimateRepository;
use App\Repository\InvoiceRepository;
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
use Symfony\Component\Security\Core\Security;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/stripe')]
class StripeController extends AbstractController
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

    // #[Route('/{id}', name: 'app_stripe_buy', methods: ['GET'])]
    // public function download(Estimate $estimate, Pdf $pdf, EstimateProductRepository $estimateProductRepository, ProductRepository $productRepository): Response
    // {
    //     $estimateProduct = $estimateProductRepository->findBy(['estimate' => $estimate]);
    //     $total = 0;
    //     foreach($estimateProduct as $product){
    //         $total += ((($product->getTotalTVA() / 100) * $product->getTotalHT()) + $product->getTotalHT()) * $product->getQuantity();
    //     }
    //     $total+= $estimateProduct[0]->getWorkforce();
    //     Stripe::setApiKey($this->getParameter('stripe.sk'));
    //     $successUrl = $this->generateUrl('back_app_invoice_update', ["id" => $estimate->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
    //     $cancelUrl = $this->generateUrl('back_app_estimate_index', [], UrlGeneratorInterface::ABSOLUTE_URL);

    //     $session = \Stripe\Checkout\Session::create([
    //         'line_items' => [
    //             [
    //                 'price_data' => [
    //                     'currency' => 'eur',
    //                     'product_data' => [
    //                         'name' => 'test',
    //                     ],
    //                     'unit_amount' => $total * 100,
    //                 ],
    //                 'quantity' => 1,
    //             ],
    //         ],
    //         "mode" => 'payment',
    //         'success_url' =>  $successUrl,
    //         'cancel_url' => $cancelUrl,
    //     ]);
    //     return new RedirectResponse($session->url);
    // }

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
}
