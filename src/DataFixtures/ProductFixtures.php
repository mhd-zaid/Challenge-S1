<?php

namespace App\DataFixtures;

use App\Entity\Product;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $object = (new Product())
            ->setTitle('Pneu')
            ->setQuantity(10)
            ->setTotalHT(100)
            ->setTotalTVA(20)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
        ;
        $manager->persist($object);

        $object = (new Product())
            ->setTitle('Huile')
            ->setQuantity(10)
            ->setTotalHT(100)
            ->setTotalTVA(20)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());

        $manager->persist($object);

        $object = (new Product())
            ->setTitle('Filtre à huile')
            ->setQuantity(10)
            ->setTotalHT(50)
            ->setTotalTVA(10)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());

        $manager->persist($object);

        $object = (new Product())
            ->setTitle('Filtre à air')
            ->setQuantity(10)
            ->setTotalHT(50)
            ->setTotalTVA(10)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());

        $manager->persist($object);

        $manager->flush();
    }
}
