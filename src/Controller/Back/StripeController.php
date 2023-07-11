<?php

namespace App\Controller\Back;

use App\Entity\Customer;
use App\Entity\Estimate;
use App\Form\EstimateType;
use App\Entity\Product;
use App\Entity\Invoice;
use App\Repository\ProductRepository;
use App\Repository\EstimateRepository;
use App\Repository\InvoiceRepository;
use App\Repository\CustomerRepository;
use App\Repository\EstimatePrestationRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Knp\Snappy\Pdf;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
#[Route('/stripe')]
#[Security('user === estimate.getCustomer() and estimate.getStatus() == "PENDING" ')]
class StripeController extends AdminController
{

    #[Route('/{id}', name: 'app_stripe_buy', methods: ['GET'])]
    public function buy(Estimate $estimate, Pdf $pdf, EstimatePrestationRepository $estimatePrestationRepository, ProductRepository $productRepository, EstimateRepository $estimateRepository): Response
    {
        $total = $estimate->getTotal($estimatePrestationRepository);

        $estimate ->setUuidSuccessPayment(Uuid::v4()->__toString());
        $estimateRepository->save($estimate, true);
        Stripe::setApiKey($this->getParameter('stripe.sk'));
        $successUrl = $this->generateUrl('back_app_invoice_update', ["id" => $estimate->getId(), "uuid" => $estimate->getUuidSuccessPayment()], UrlGeneratorInterface::ABSOLUTE_URL);
        $cancelUrl = $this->generateUrl('back_app_estimate_index', [], UrlGeneratorInterface::ABSOLUTE_URL);
        
        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'test',
                        ],
                        'unit_amount' => $total * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
            "mode" => 'payment',
            'success_url' =>  $successUrl,
            'cancel_url' => $cancelUrl,
        ]);
        return new RedirectResponse($session->url);
    }
}
