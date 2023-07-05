<?php

namespace App\Controller\Back;

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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    #[Security('is_granted("ROLE_SUPER_ADMIN") or is_granted("ROLE_ADMIN")')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('back/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    #[Security('is_granted("ROLE_SUPER_ADMIN") or is_granted("ROLE_ADMIN")')]
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
            ->htmlTemplate('back/email/confirmEmail.html.twig')
            ->context([
                'user' => $user,
            ]);

            $mailer->send($email);

            return $this->redirectToRoute('back_app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    #[Security('user.getId() == id or is_granted("ROLE_SUPER_ADMIN") or is_granted("ROLE_ADMIN")')]
    public function show(User $user): Response
    {
        return $this->render('back/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    #[Security('user.getId() == id or is_granted("ROLE_SUPER_ADMIN") or is_granted("ROLE_ADMIN")')]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('back_app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    #[Security('is_granted("ROLE_SUPER_ADMIN") or is_granted("ROLE_ADMIN")')]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('back_app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
