<?php

namespace App\Controller\Front;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

#[Route('/customer')]
class CustomerController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/new', name: 'app_customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CustomerRepository $customerRepository): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $customer = $customerRepository->find($form->get('id')->getData());
            $customer->setId(intval($form->get('id')->getData()));


            $customer->setLastname($form->get('lastname')->getData());
            $customer->setFirstname($form->get('firstname')->getData());
            $customer->setEmail($form->get('email')->getData());
            $customer->setPlainPassword($form->get('plainPassword')->getData());
            $customer->setAddress($form->get('address')->getData());
            $customer->setPhone($form->get('phone')->getData());

            $customer->setValidationToken(Uuid::v4()->__toString());            
            $email = (new TemplatedEmail())
            ->from("zaidmouhamad@gmail.com")
            ->to($customer->getEmail())
            ->subject('Confirm email')
            ->htmlTemplate('back/email/confirmEmail.html.twig')
            ->context([
                'user' => $customer,
            ]);
            $this->mailer->send($email);
            
            $customerRepository->save($customer, true);
            return $this->redirectToRoute('front_default_index', [], Response::HTTP_SEE_OTHER);
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

