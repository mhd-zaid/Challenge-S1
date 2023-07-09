<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Entity\Estimate;
use App\Entity\Invoice;
use App\Entity\EstimatePrestation;

class AddQuantityCommand extends Command
{
    protected static $defaultName = 'app:add-quantity';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add quantity to a product if date is expired')
            ->setHelp('This command adds quantity to a product and set Status refused.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $date = new \DateTime();
    
        $estimates = $this->entityManager->getRepository(Estimate::class)->getEstimateByDate($date);
        foreach($estimates as $estimate){
            $estimatePrestations = $this->entityManager->getRepository(EstimatePrestation::class)->findBy(['estimate' => $estimate]);
            foreach($estimatePrestations as $estimatePrestation){
                $prestation = $estimatePrestation->getPrestation();
                foreach($prestation->getPrestationProducts() as $prestationProduct){
                    $productUpdate = $prestationProduct->getProduct();
                    $productUpdate->setQuantity($prestationProduct->getProduct()->getQuantity() + $prestationProduct->getQuantity());
                    $this->entityManager->getRepository(Product::class)->save($productUpdate, true);
                }
                
            }
            $estimate->setStatus('REFUSED');
            $this->entityManager->getRepository(Estimate::class)->save($estimate, true);
            $invoice = $estimate->getInvoice();
            $invoice->setStatus('REFUSED');
            $this->entityManager->getRepository(Invoice::class)->save($invoice, true);  
        }

    
        return Command::SUCCESS;
    }
}
