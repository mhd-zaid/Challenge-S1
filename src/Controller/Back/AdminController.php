<?php


namespace App\Controller\Back;

use App\Repository\CustomerRepository;
use App\Repository\EstimatePrestationRepository;
use App\Repository\InvoicePrestationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/estimate')]
class AdminController extends AbstractController
{

    public function checkId(int $id, CustomerRepository $customerRepository): bool
    {
        $user = $customerRepository->find($id);
        if ($user === null) {
            return false;
        }

        return true;
    }

    public function generateCustomerId(CustomerRepository $customerRepository): int
    {
        $id = 0;
        do {
            $id = random_int(10000, 20000);
        } while ($this->checkId($id, $customerRepository));
        return $id;
    }

    public function getEstimateTotal(array $estimates, EstimatePrestationRepository $estimatePrestationRepository): array
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

    public function getInvoiceTotal(array $invoices, InvoicePrestationRepository $invoicePrestationRepository): array
    {
        $invoicesTotal = [];
        foreach ($invoices as $invoice) {
            $invoicesTotal[] = [
                'id' => $invoice->getId(),
                'total' => $invoice->getTotal($invoicePrestationRepository)

            ];
        }
        return $invoicesTotal;
    }
}
