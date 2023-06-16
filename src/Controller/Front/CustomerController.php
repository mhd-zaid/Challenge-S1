<?php

namespace App\Controller\Front;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer')]
class CustomerController extends AbstractController
{

    #[Route('/new', name: 'app_customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CustomerRepository $customerRepository): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $customerRepository->save($customer, true);
            return $this->redirectToRoute('front_app_customer_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('front/customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }
}
