<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class DefaultController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    #[Route('/', name: 'default_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('front/default/index.html.twig');
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
}
