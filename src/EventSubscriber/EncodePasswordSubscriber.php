<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EncodePasswordSubscriber implements EventSubscriberInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher){}

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        /** @var User | Customer $object */
        if ($object instanceof User || $object instanceof Customer) {
            $this->updatePassword($object);
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        /** @var User | Customer $object */
        if ($object instanceof User || $object instanceof Customer) {
            $this->updatePassword($object);
        }
    }

    private function updatePassword(User | Customer $object): void
    {
        if ($object->getPlainPassword()) {
            $object->setPassword($this->passwordHasher->hashPassword($object, $object->getPlainPassword()));
        }
    }
}