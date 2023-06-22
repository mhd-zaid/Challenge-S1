<?php

namespace App\Controller\Back;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class AccountingController extends AbstractController
{
    
    #[Route('/accounting', name: 'app_accounting_index', methods: ['GET'])]
    public function index(ChartBuilderInterface $chartBuilder): Response
    {

        //Afficher Les Factures et pouvoir filtrer
        //Export CSV Comptable

        return $this->render('back/accounting/index.html.twig');
    }
}
