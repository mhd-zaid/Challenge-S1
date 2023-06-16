<?php

namespace App\Controller\Back;

use App\Repository\UserRepository;
use App\Repository\CustomerRepository;
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
}
