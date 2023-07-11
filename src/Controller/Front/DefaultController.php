<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends AbstractController
{    
    #[Route('/', name: 'default_index', methods: ['GET', 'POST'])]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
//        return $this->render('front/default/index.html.twig');
        return $this->redirectToRoute('app_login');
    }

//    public function index(AuthenticationUtils $authenticationUtils): Response
//    {
//        //get the login error if there is one
//        $error = $authenticationUtils->getLastAuthenticationError();
//        // last username entered by the user
//        $lastUsername = $authenticationUtils->getLastUsername();
//        return $this->render('front/default/index.html.twig', [
//            'last_username' => $lastUsername,
//            'error' => $error
//        ]);
////        return $this->redirectToRoute('app_login');
//    }
}


