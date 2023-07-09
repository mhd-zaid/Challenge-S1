<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Company;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $object = (new Category())
            ->setName("Mécanique")
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
        ;
        $manager->persist($object);


        $object = (new Category())
            ->setName("Carrosserie")
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
        ;
        $manager->persist($object);

        $object = (new Category())
            ->setName("Pneumatique")
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
        ;
        $manager->persist($object);

        $object = (new Category())
            ->setName("Vidange")
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
        ;
        $manager->persist($object);
        
        $manager->flush();
    }
}
