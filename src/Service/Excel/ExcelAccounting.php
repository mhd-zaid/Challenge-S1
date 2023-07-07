<?php

namespace App\Service\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelAccounting {

    private int $row;

    public function __construct(private ExcelGenerator $excelGenerator)
    {
        $excelGenerator->setExcel(new SpreadSheet());
    }
    
   /**
    * Ecrit les données dans l'excel
    *
    * @param array $data
    * @param array $formData
    * @param array $header
    * @return void
    */
    public function writeData(array $data, array $header): void
    {
        $activeSheet = $this->excelGenerator->getExcel()->getActiveSheet();
        foreach ($data as $invoice) {
            $activeSheet->insertNewRowBefore($this->row);
            $columnLetter = 'A';
            foreach ($header as $column) {
                switch ($column) {
                    case 'DEVIS N°':
                        $activeSheet->setCellValue($columnLetter.$this->row, $invoice->getId());
                        break;
                    case 'CLIENT ID':
                        $activeSheet->setCellValue($columnLetter.$this->row, $invoice->getCustomer()->getId());
                        break;
                    case 'NOM CLIENT':
                        $activeSheet->setCellValue($columnLetter.$this->row, $invoice->getCustomer()->getLastname());
                        break;
                    case 'PRÉNOM CLIENT':
                        $activeSheet->setCellValue($columnLetter.$this->row, $invoice->getCustomer()->getFirstname());
                        break;
                    case 'EMAIL CLIENT':
                        $activeSheet->setCellValue($columnLetter.$this->row, $invoice->getCustomer()->getEmail());
                        break;
                    case 'STATUT':
                        $activeSheet->setCellValue($columnLetter.$this->row, $invoice->getStatus());
                        break;
                    default:
                        break;
                }
                
                $activeSheet->getColumnDimension($columnLetter)->setAutoSize(true);
                $activeSheet->getStyle($columnLetter.$this->row)->getFont()->setBold(false);
                $columnLetter++;
            }
            $this->row++;
        }
    }

    /**
     * Ecrit la première ligne sur l'excel
     *
     * @param array $header
     * @return void
     */
    public function writeHeader(array $header): void
    {
        $columnLetter = 'A';
        $activeSheet = $this->excelGenerator->getExcel()->getActiveSheet();
        $activeSheet->insertNewRowBefore($this->row);
            foreach ($header as $field) {
                $activeSheet->setCellValue($columnLetter.$this->row, $field)->getStyle($columnLetter.$this->row)->getFont()->setBold(true);
                $columnLetter++;
            }
            $this->row++;
    }

    public function getRow() : int
    {
        return $this->row;
    }

    public function setRow(int $row): void
    {
        $this->row = $row;
    }

    public function getExcelGenerator() : ExcelGenerator
    {
        return $this->excelGenerator;
    }
}