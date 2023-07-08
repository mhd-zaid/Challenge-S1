<?php
namespace App\Controller\Back;

use App\Entity\Customer;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\CustomerType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Twig\Environment;

#[Route('/account')]
class AccountController extends AdminController
{

    #[Route('/{id}', name: 'app_account_show', methods: ['GET'])]
    public function show(EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->findOneBy(['id' => $this->getUser()]);
        $customer = $em->getRepository(Customer::class)->findOneBy(['id' => $this->getUser()]);
        if($user){
            return $this->render('back/account/show.html.twig', [
                'user' => $user,
                'entity' => 'user'
            ]);
        }
        else if($customer){
            return $this->render('back/account/show.html.twig', [
                'customer' => $customer,
                'entity' => 'customer'
            ]);
        }else{
            return $this->redirectToRoute('back_default_index');
        }
    }

    #[Route('/{id}/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->findOneBy(['id' => $this->getUser()]);
        $customer = $em->getRepository(Customer::class)->findOneBy(['id' => $this->getUser()]);

        if($user){
            $form = $this->createForm(AccountType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->getRepository(User::class)->save($user, true);
                return $this->redirectToRoute('back_app_account_show', ['id'=>$user->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('back/account/edit.html.twig', [
                'user' => $user,
                'form' => $form,
                'entity' => 'user'
            ]);
        }
        else if($customer){
            $form = $this->createForm(AccountType::class, $customer);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->getRepository(Customer::class)->save($customer, true);
                return $this->redirectToRoute('back_app_account_show', ['id'=>$customer->getId()], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('back/account/edit.html.twig', [
                'customer' => $customer,
                'form' => $form,
                'entity' => 'customer'
            ]);
        }else{
            return $this->redirectToRoute('back_default_index');
        }
    }
}