<?php

namespace App\Controller\Back;

use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Service\Excel\ExcelAccounting;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Route('/accounting')]
#[Security('is_granted("ROLE_ACCOUNTANT")')]
class AccountingController extends AdminController
{
    
    #[Route('/', name: 'app_accounting_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        //Export CSV Comptable
        $invoices  = $em->getRepository(Invoice::class)->findAll();

        return $this->render('back/accounting/index.html.twig',['invoices'=>$invoices]);
    }

    #[Route('/filter', name: 'app_accounting_filter', methods: ['GET'])]
    public function filter(Request $request,EntityManagerInterface $em): Response
    {   
        $filters = [
            'status' => $request->query->get('states'),
            'dateStart' => $request->query->get('dateStart'),
            'dateEnd' => $request->query->get('dateEnd'),
            'customer' => $request->query->get('customer'),
        ];
        
        $filteredParams = array_filter($filters, function($value) {
            return !empty($value);
        });

        if (empty($filteredParams)) {
            
            return $this->redirectToRoute('back_app_accounting_index');
        }
        
        $invoices  = $em->getRepository(Invoice::class)->findByFilter($filteredParams);
        
        return $this->render('back/accounting/index.html.twig',['invoices'=>$invoices,'filter'=>http_build_query($filteredParams)]);
    }

    #[Route('/export/{filter}', name: 'app_accounting_export', methods: ['GET'])]
    public function export(Request $request,EntityManagerInterface $em, ExcelAccounting $excelAccounting,?string $filter = null): Response
    {   
        parse_str($filter, $params);
        $invoices = $em->getRepository(Invoice::class)->findAll();
        $date = new DateTime('now');
        $header = ['DEVIS N°','CLIENT ID','NOM CLIENT','PRÉNOM CLIENT',	'EMAIL CLIENT','STATUT'];
        if ($filter != null) {
            $invoices  = $em->getRepository(Invoice::class)->findByFilter($params);
        }
        
        $filename = "Factures-" . $date->format('Y-m-d_H:m:s') . ".xlsx";
        $excelAccounting->setRow(1);
        $excelAccounting->getExcelGenerator()->setSheetTitle("Factures");
        $excelAccounting->writeHeader($header);
        $excelAccounting->writeData($invoices,$header);
        $excelAccounting->getExcelGenerator()->generate($filename);

        return $excelAccounting->getExcelGenerator()->generate($filename);
    }

}
