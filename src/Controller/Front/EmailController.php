<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Uid\Uuid;

class EmailController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/email', name: 'app_email')]
    public function index(): Response
    {
        return $this->render('email/index.html.twig', [
            'controller_name' => 'EmailController',
        ]);
    }

    #[Route('/validate/{id}/{token}', name: 'app_email_validate', methods: ['GET'])]
    public function confirmMail(Request $request, int $id,string $token, UserRepository $userRepository, CustomerRepository $customerRepository): Response
    {
        $user = $userRepository->findOneBy([
            'id' => $id,
            'validationToken' => $token
        ]);

        $customer = $customerRepository->findOneBy([
            'id' => $id,
            'validationToken' => $token
        ]);

        if (!empty($user)) {
            $user->setIsValidated(true);
            $userRepository->save($user, true);
        }

        if (!empty($customer)) {
            $customer->setIsValidated(true);
            $customerRepository->save($customer, true);
        }

        return $this->redirectToRoute('back_default_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/verifEmail', name: 'verifEmail', methods: ['GET'])]
    public function verifEmail(): Response
    {
        $user = $this->security->getUser();
        if (!$user->getIsValidated()) {
            return $this->render('back/user/sendEmail.html.twig',['user'=>$user]);
        }
        return $this->render('back/default/index.html.twig');
    }

    #[Route('/confirmEmail/{id}', name: 'app_send_confirm_email', methods: ['GET'])]
    public function confirmEmail(int $id,UserRepository $userRepository, CustomerRepository $customerRepository,MailerInterface $mailer): Response
    {
        $user = $userRepository->findOneBy([
            'id' => $id,
        ]);

        $customer = $customerRepository->findOneBy([
            'id' => $id,
        ]);

        if (!empty($user)) {
            $user->setValidationToken(Uuid::v4()->__toString());
            $userRepository->save($user, true);
            
            $email = (new TemplatedEmail())
            ->from("zaidmouhamad@gmail.com")
            ->to($user->getEmail())
            ->subject('Confirm email')
            ->htmlTemplate('back/email/confirmEmail.html.twig')
            ->context([
                'user' => $user,
            ]);
            $mailer->send($email);
            
            return $this->render('back/user/sendEmail.html.twig',['message'=>'send','user'=>$user]);
        }

        if (!empty($customer)) {
            $customer->setValidationToken(Uuid::v4()->__toString());
            $customerRepository->save($customer, true);
            
            $email = (new TemplatedEmail())
            ->from("zaidmouhamad@gmail.com")
            ->to($customer->getEmail())
            ->subject('Confirm email')
            ->htmlTemplate('back/email/confirmEmail.html.twig')
            ->context([
                'user' => $customer,
            ]);
            // dump($customer);
            // die;
            $mailer->send($email);
            
            return $this->render('back/user/sendEmail.html.twig',['message'=>'send','user'=>$customer]);
        }
        

        return $this->redirectToRoute('back_default_index');
    }
}
