<?php

namespace App\DataFixtures;

use App\Entity\Company;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CompanyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $siret = $faker->numerify('#########000##');
        $tva = $faker->regexify('FR[0-9]{2}[0-9]{9}');

        $object = (new Company())
            ->setName("Presta Auto")
            ->setEmail($faker->email)
            ->setOwnerFirstName($faker->firstName)
            ->setOwnerLastName($faker->lastName)
            ->setDateOfCreation(new DateTime())
            ->setAddress($faker->address)
            ->setPhoneNumber($faker->phoneNumber)
            ->setSiret($siret)
            ->setTva($tva)
            ->setLanguage($faker->languageCode)
            ->setCurrency($faker->currencyCode)
            ->setTheme('dark')
            ->setDescription($faker->text)
        ;
        $manager->persist($object);

        $manager->flush();
    }
}
