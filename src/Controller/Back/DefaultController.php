<?php

namespace App\Controller\Back;

use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class DefaultController extends AbstractController
{
    
    #[Route('/', name: 'default_index', methods: ['GET'])]
    public function index(): Response
    {

        return $this->render('back/default/index.html.twig');
    }

    #[Route('/confirmEmail/{id}', name: 'app_send_confirm_email', methods: ['GET'])]
    public function confirmEmail(int $id,UserRepository $userRepository,MailerInterface $mailer): Response
    {
        $user = $userRepository->findOneBy([
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
        

        return $this->redirectToRoute('back_default_index');
    }
}
