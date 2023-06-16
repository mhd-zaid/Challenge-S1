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
        //utilise $request to get ID PARAM
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(isset($_GET['id']) && $_GET['id']){
            $customer = $customerRepository->find($form->get('id')->getData());
            $customer->setId($_GET['id']);
        }else{
            $id = $this->generateCustomerId($customerRepository);
            $customer->setId($id);
        }
            $customer->setLastname($form->get('lastname')->getData());
            $customer->setFirstname($form->get('firstname')->getData());
            $customer->setEmail($form->get('email')->getData());
            $customer->setPlainPassword($form->get('plainPassword')->getData());
            $customer->setAddress($form->get('address')->getData());
            $customer->setPhone($form->get('phone')->getData());

            $customerRepository->save($customer, true);
            return $this->redirectToRoute('front_app_customer_new', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('front/customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }
    public function generateCustomerId(CustomerRepository $customerRepository): int
    {
        $id = 0;
        do {
            $id = random_int(10000, 20000);
        } while ($this->checkId($id, $customerRepository));
        return $id;
    }

    public function checkId(int $id, CustomerRepository $customerRepository): bool
    {
        $user = $customerRepository->find($id);
        if($user === null){
            return false;
        }

        return true;
    }
}

