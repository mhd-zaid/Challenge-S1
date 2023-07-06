<?php

namespace App\Controller\Back;

use App\Repository\EstimateRepository;
use App\Repository\InvoiceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\Uid\Uuid;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DefaultController extends AbstractController
{
    
    #[Route('/', name: 'default_index', methods: ['GET'])]
    public function index(ChartBuilderInterface $chartBuilder, EstimateRepository $estimateRepository, InvoiceRepository $invoiceRepository, Security $security): Response
    {
        $chart = $chartBuilder->createChart(Chart::TYPE_PIE);

        if($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MECHANIC') || $this->isGranted('ROLE_ACCOUNTANT')){
            $estimates = $estimateRepository->findAll();
            $invoices = $invoiceRepository->findAll();
        }else{
            $estimates = $estimateRepository->findBy(['client' => $security->getUser()->getId()]);
            $invoices = $invoiceRepository->findBy(['client' => $security->getUser()->getId()]);
        }
        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => ["#54bebe", "#76c8c8", "#98d1d1", "#badbdb"],
                    'data' => [10, 5, 2, 20],
                ],
            ],
        ]);

        $chart->setOptions([
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ]);

        return $this->render('back/default/index.html.twig',[
            'chart' => $chart,
            'estimates' => $estimates,
            'invoices' => $invoices
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
