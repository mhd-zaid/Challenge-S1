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

#[Route('/account')]
class AccountController extends AbstractController
{

    #route pour afficher le compte d'un utilisateur
    #[Route('/{id}', name: 'app_account_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('back/account/show.html.twig', [
            'user' => $user,
        ]);
    }

    #route pour modifier le compte d'un utilisateur
    #[Route('/{id}/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        dump($form);
        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('back_app_account_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/account/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}