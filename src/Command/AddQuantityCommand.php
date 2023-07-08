<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Entity\Estimate;

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
            ->setDescription('Add quantity to a product')
            ->setHelp('This command adds quantity to a product.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Récupérer le produit à mettre à jour (vous pouvez utiliser un identifiant spécifique ou un autre moyen pour récupérer le produit)
        $productId = 1; // Remplacez 1 par l'ID du produit que vous souhaitez mettre à jour
        $product = $this->entityManager->getRepository(Product::class)->find($productId);
        $estimate = $this->entityManager->getRepository(Estimate::class)->find($productId);


        // Ajouter la quantité souhaitée au produit
        $quantityToAdd = 1;
        $product->setQuantity($product->getQuantity() + $quantityToAdd);

        // Enregistrer les modifications en base de données
        $this->entityManager->flush();

        $output->writeln('Quantity added to the product.');

        return Command::SUCCESS;
    }
}
