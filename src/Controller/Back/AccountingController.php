<?php

namespace App\Controller\Back;

use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AccountingController extends AbstractController
{
    
    #[Route('/accounting', name: 'app_accounting_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        //Export CSV Comptable
        $invoices  = $em->getRepository(Invoice::class)->findAll();

        return $this->render('back/accounting/index.html.twig',['invoices'=>$invoices]);
    }

    #[Route('/accounting/filter', name: 'app_accounting_filter', methods: ['GET'])]
    public function filter(Request $request,EntityManagerInterface $em): Response
    {   
        $filters = [
            'status' => $request->query->get('states'),
            'dateStart' => $request->query->get('dateStart'),
            'dateEnd' => $request->query->get('dateEnd'),
            'estimate' => $request->query->get('estimate'),
        ];
        
        $filteredParams = array_filter($filters, function($value) {
            return !empty($value);
        });

        if (empty($filteredParams)) {
            
            return $this->redirectToRoute('back_app_accounting_index');
        }
        
        $invoices  = $em->getRepository(Invoice::class)->findByFilter($filteredParams);
        
        return $this->render('back/accounting/index.html.twig',['invoices'=>$invoices]);
    }
}
