<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $pwd = '$2y$13$cxyypcYcyj4sQhaeLhojvucbBwbWo789iF/Aqqsvm2Rpcu/jNxIf6';

        $object = (new Customer())
            ->setEmail('customer@user.fr')
            ->setLastname($faker->lastName)
            ->setFirstname($faker->firstName)
            ->setRoles([])
            ->setPassword($pwd)
            ->setAddress($faker->address)
            ->setPhone($faker->phoneNumber)
            ->setValidationToken("")
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
        ;
        $manager->persist($object);

        $object = (new Customer())
            ->setEmail('customer2@user.fr')
            ->setLastname($faker->lastName)
            ->setFirstname($faker->firstName)
            ->setRoles([])
            ->setPassword($pwd)
            ->setAddress($faker->address)
            ->setPhone($faker->phoneNumber)
            ->setIsValidated(true)
            ->setValidationToken("")
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
        ;
        $manager->persist($object);
    }
}
