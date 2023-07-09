<?php

namespace App\Controller\Back;

use App\Entity\Customer;
use App\Entity\Estimate;
use App\Entity\EstimatePrestation;
use App\Entity\Invoice;
use App\Entity\InvoicePrestation;
use App\Repository\EstimatePrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
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
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AdminController
{
    
    #[Route('/', name: 'default_index', methods: ['GET'])]
    public function index(Request $request,ChartBuilderInterface $chartBuilder,EntityManagerInterface $em, Security $security, PaginatorInterface $paginator): Response
    {
        $estimateRepository = $em->getRepository(Estimate::class);
        $invoiceRepository = $em->getRepository(Invoice::class);

        $estimates = $estimateRepository->findBy(['customer'=>$this->getUser()],null,5);
        $invoices = $invoiceRepository->findBy(['customer'=>$this->getUser()],null,5);
        $cutomers = $em->getRepository(Customer::class)->findAll();
        $invoicesPaid = $em->getRepository(Invoice::class)->findBy(['status'=>'PAID']);
        $invoicesPending = $em->getRepository(Invoice::class)->findBy(['status'=>'PENDING']);

        if ($this->isGranted('ROLE_MECHANIC')) {
            $estimates = $em->getRepository(Estimate::class)->findAll();
            $invoices = $em->getRepository(Invoice::class)->findAll();
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_PIE);

        if($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MECHANIC') || $this->isGranted('ROLE_ACCOUNTANT')){
            $estimates = $estimateRepository->findAll();
            $invoices = $invoiceRepository->findAll();
        }else{
            $estimates = $estimateRepository->findBy(['customer' => $security->getUser()->getId()]);
            $invoices = $invoiceRepository->findBy(['customer' => $security->getUser()->getId()]);
        }
        $estimatesPagination = $paginator->paginate(
            $estimates,
            $request->query->getInt('page', 1),
            5
        );
        $invoicesPagination = $paginator->paginate(
            $invoices,
            $request->query->getInt('page', 1),
            5
        );
        $estimatesTotal = $this->getEstimateTotal($estimates,$em->getRepository(EstimatePrestation::class));
        $invoicesTotal = $this->getInvoiceTotal($invoices,$em->getRepository(InvoicePrestation::class));

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
            'estimatesTotal'=> $estimatesTotal,
            'invoicesTotal'=> $invoicesTotal,
            'invoices'=>$invoices,
            'customers'=>$cutomers,
            'invoicesPaid'=>$invoicesPaid,
            'invoicesPending'=>$invoicesPending,
            'invoicesPagination' => $invoicesPagination,
            'estimatesPagination' => $estimatesPagination,
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

    public function getEstimateTotal(Array $estimates,EstimatePrestationRepository $estimatePrestationRepository): array
    {
        $estimatesTotal = [];
        foreach ($estimates as $estimate) {
            $estimatesTotal[] = [
                'id' => $estimate->getId(),
                'total' => $estimate->getTotal($estimatePrestationRepository)

            ];
        }  
        return $estimatesTotal; 
    }
}
