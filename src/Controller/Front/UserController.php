<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Twig\Environment;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('front/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository,MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        dump($form);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setValidationToken(Uuid::v4()->__toString());
            $userRepository->save($user, true);

            $email = (new TemplatedEmail())
            ->from("zaidmouhamad@gmail.com")
            ->to($user->getEmail())
            ->subject('Confirm email')
            ->htmlTemplate('front/email/confirmEmail.html.twig')
            ->context([
                'user' => $user,
            ]);

            $mailer->send($email);

            return $this->redirectToRoute('front_app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('front/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('front_app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('front_app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/validate/{id}/{token}', name: 'app_user_validate', methods: ['GET'])]
    public function confirmMail(Request $request, int $id,string $token, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy([
            'id' => $id,
            'validationToken' => $token
        ]);

        if (!empty($user)) {
            $user->setIsValidated(true);
            $userRepository->save($user, true);
        }

        return $this->redirectToRoute('front_default_index', [], Response::HTTP_SEE_OTHER);
    }
}
