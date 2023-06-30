<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AccountingController extends AbstractController
{
    
    #[Route('/accounting', name: 'app_accounting_index', methods: ['GET'])]
    public function index(): Response
    {
        //Export CSV Comptable
    
        return $this->render('back/accounting/index.html.twig');
    }

    #[Route('/accounting/filter', name: 'app_accounting_filter', methods: ['GET'])]
    public function filter(Request $request): Response
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
        

        
        return $this->render('back/accounting/index.html.twig');
    }
}
