<?php

namespace App\Controller\Back;

use App\Entity\Customer;
use App\Entity\Estimate;
use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\Uid\Uuid;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends AbstractController
{
    
    #[Route('/', name: 'default_index', methods: ['GET'])]
    #[Security('is_granted("ROLE_CUSTOMER")')]
    public function index(ChartBuilderInterface $chartBuilder,EntityManagerInterface $em): Response
    {
        $estimates = $em->getRepository(Estimate::class)->findBy(['client'=>$this->getUser()],null,5);
        $invoices = $em->getRepository(Invoice::class)->findBy(['client'=>$this->getUser()],null,5);
        $cutomers = $em->getRepository(Customer::class)->findAll();
        $invoicesPaid = $em->getRepository(Invoice::class)->findBy(['status'=>'PAID']);
        $invoicesPending = $em->getRepository(Invoice::class)->findBy(['status'=>'PENDING']);
        if ($this->isGranted('ROLE_MECHANIC')) {
            $estimates = $em->getRepository(Estimate::class)->findAll();
            $invoices = $em->getRepository(Invoice::class)->findAll();
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_PIE);
        
        $chart->setData([
            'labels' => ['Nouveau Clients', 'Devis en attente', 'Prestation en attente', 'Presation effectuée'],
            'datasets' => [
                [
                    'label' => 'dashboard',
                    'backgroundColor' => ["#54bebe", "#76c8c8", "#98d1d1", "#badbdb"],
                    'data' => [count($cutomers),count($estimates) , 8, 43],
                ],
            ],
        ]);

        if ($this->isGranted('ROLE_ACCOUNTANT') and !$this->isGranted('ROLE_ADMIN')) {
            $invoices = $em->getRepository(Invoice::class)->findAll();
            $chart->setData([
                'labels' => ['Facture Payée', 'Facture en attente', 'Facture total'],
                'datasets' => [
                    [
                        'label' => 'dashboard',
                        'backgroundColor' => ["#54bebe", "#76c8c8", "#98d1d1", "#badbdb"],
                        'data' => [count($invoicesPaid),count($invoicesPending),count($invoices)]
                    ],
                ],
            ]);
        }

        $chart->setOptions([
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ]);

        return $this->render('back/default/index.html.twig',[
            'chart' => $chart,
            'estimates'=>$estimates,
            'invoices'=>$invoices,
            'customers'=>$cutomers,
            'invoicesPaid'=>$invoicesPaid,
            'invoicesPending'=>$invoicesPending
        ]);
    }

    #[Route('/pdf', name: 'pdf', methods: ['GET'])]
    public function pdf(Pdf $pdf): PdfResponse
    {
        $html = $this->renderView('back/pdf/estimate.html.twig');

        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            'file.pdf'
        );
    }
}
