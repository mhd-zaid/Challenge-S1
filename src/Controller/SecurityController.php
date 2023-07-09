<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\PasswordForgetType;
use App\Form\ResetPasswordType;
use App\Entity\User;
use App\Entity\Customer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Uid\Uuid;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/forget', name: 'app_forget_password')]
    public function forget(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {

        $form = $this->createForm(PasswordForgetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $em->getRepository(User::class)->findOneBy([
                'email' => $form->get('email')->getData()
            ]);

            $customer = $em->getRepository(Customer::class)->findOneBy([
                'email' => $form->get('email')->getData()
            ]);

            if(!empty($user)){
                $user->setValidationToken(Uuid::v4()->__toString());
                $em->getRepository(User::class)->save($user, true);

                $email = (new TemplatedEmail())
                ->from("zaidmouhamad@gmail.com")
                ->to($user->getEmail())
                ->subject('Reset Password')
                ->htmlTemplate('security/forgetPassword/confirmReset.html.twig')
                ->context([
                    'user' => $user,
                ]);
    
                $mailer->send($email);
            }else{
                $customer->setValidationToken(Uuid::v4()->__toString());
                $em->getRepository(Customer::class)->save($customer, true);

                $email = (new TemplatedEmail())
                ->from("zaidmouhamad@gmail.com")
                ->to($customer->getEmail())
                ->subject('Reset Password')
                ->htmlTemplate('security/forgetPassword/confirmReset.html.twig')
                ->context([
                    'user' => $customer,
                ]);
                $mailer->send($email);
            }
        }

        return $this->renderForm('security/forgetPassword/forget.html.twig', [
            'form' => $form
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/reset/{id}/{token}', name: 'app_reset_password', methods: ['GET', 'POST'])]
    public function resetPassword(Request $request, int $id,string $token, UserRepository $userRepository, CustomerRepository $customerRepository): Response
    {
        $user = $userRepository->findOneBy([
            'id' => $id,
            'validationToken' => $token
        ]);

        $customer = $customerRepository->findOneBy([
            'id' => $id,
            'validationToken' => $token
        ]);
        if(empty($user) && empty($customer)){
            //throw erreur
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!empty($user)){
                $user->setPlainPassword($form->get('plainPassword')->getData());
                $userRepository->save($user, true);
            }else{
                $customer->setPlainPassword($form->get('plainPassword')->getData());
                $customerRepository->save($customer, true);
            }

        }
        return $this->renderForm('security/resetPassword/reset.html.twig', [
            'form' => $form
        ]);

        // return $this->redirectToRoute('back_default_index', [], Response::HTTP_SEE_OTHER);
    }
}
