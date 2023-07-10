<?php
namespace App\Controller\Back;

use App\Entity\Customer;
use App\Entity\User;
use App\Form\AccountType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Route('/account')]
class AccountController extends AdminController
{

    #[Route('/', name: 'app_account_show', methods: ['GET'])]
    #[Security('user === user' )]
    public function show(EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->findOneBy([
            'email' => $this->getUser()->getEmail()
    ]);
        $customer = $em->getRepository(Customer::class)->findOneBy(['email' => $this->getUser()->getEmail()]);
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
    #[Security('user === user')]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->findOneBy(['id' => $this->getUser()]);
        $customer = $em->getRepository(Customer::class)->findOneBy(['id' => $this->getUser()]);
        if($user){
            $form = $this->createForm(AccountType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->getRepository(User::class)->save($user, true);
                $this->addFlash(
                    'success',
                    'L\'utilisateur a bien été modifié'
                );
                return $this->redirectToRoute('back_app_account_show', ['id'=>$user->getId()], Response::HTTP_SEE_OTHER);

            }elseif ($form->isSubmitted() && !$form->isValid()) {
                $this->addFlash(
                    'error',
                    'Une erreur est survenue lors de la modification de l\'utilisateur'
                );
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
                $this->addFlash(
                    'success',
                    'Le client a bien été modifié'
                );
                return $this->redirectToRoute('back_app_account_show', Response::HTTP_SEE_OTHER);
            }elseif ($form->isSubmitted() && !$form->isValid()) {
                $this->addFlash(
                    'error',
                    'Une erreur est survenue lors de la modification du client'
                );
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