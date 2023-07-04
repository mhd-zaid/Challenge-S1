<?php

namespace App\Service\Excel;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelGenerator
{
    private SpreadSheet $excel;

    /**
     * Génère un excel à partir de des données
     *
     * @param string $filename
     * 
     * @return Response
     */
    public function generate(string $filename): Response
    {
        $writer = new Xlsx($this->excel);
        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        //Permet de convertir les caractère spéciaux par leur équivalent en normal
        $filename = transliterator_transliterate('Any-Latin;Latin-ASCII;[\u0080-\u7fff] remove',$filename);
        $response->headers->set('Content-Type','application/vnd.ms-excel');
        $response->headers->set('Content-Disposition',"attachment;filename=$filename");
        $response->headers->set('Cache-Control','must-revalidate, post-check=0, pre-check=0');
        $response->headers->set('Expire','0');
        
        return $response;
    }

    public function setSheetTitle(string $title): void
    {
        $activeSheet = $this->excel->getActiveSheet();
        $activeSheet->setTitle($title);
    }
    

    public function getExcel() : SpreadSheet
    {
        return $this->excel;
    }

    public function setExcel(SpreadSheet $excel): void
    {
        $this->excel = $excel;
    }

}