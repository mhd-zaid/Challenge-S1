<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Controller\Back\CustomerController;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $pwd = '$2y$13$cxyypcYcyj4sQhaeLhojvucbBwbWo789iF/Aqqsvm2Rpcu/jNxIf6';

        for ($i = 0; $i < 10; $i++) {
            $clientId = random_int(10000, 20000);
            $firstname = $faker->firstName;
            $lastname = $faker->lastName;
            $email = str_replace(" ", "", strtolower($firstname).'.'.strtolower($lastname).'@user.fr');
            $object = (new Customer())
                ->setId($clientId)
                ->setLastname($lastname)
                ->setFirstname($firstname)
                ->setEmail($email)
                ->setPassword($pwd)
                ->setAddress($faker->address)
                ->setPhone($faker->phoneNumber)
                ->setIsValidated($faker->boolean)
                ->setValidationToken("")
                ->setRoles(['ROLE_CUSTOMER'])
                ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                ->setCity($faker->city)
                ->setZipcode(str_replace(" ", "", $faker->postcode))
                ->setCountry($faker->country)
                ->setLanguage('fr')
        ;
            $manager->persist($object);
        }

        $clientId = random_int(10000, 20000);
        $object = (new Customer())
            ->setId($clientId)
            ->setLastname('Makan')
            ->setFirstname('Kamissoko')
            ->setEmail('makan.kamissoko@hotmail.fr')
            ->setPassword($pwd)
            ->setAddress($faker->address)
            ->setPhone($faker->phoneNumber)
            ->setIsValidated(true)
            ->setValidationToken("")
            ->setRoles(['ROLE_CUSTOMER'])
            ->setCreatedAt($faker->dateTimeBetween('-6 months'))
            ->setCity($faker->city)
            ->setZipcode(str_replace(" ", "", $faker->postcode))
            ->setCountry($faker->country)
            ->setLanguage('en')
        ;
        $manager->persist($object);

        $manager->flush();
    }
}
