<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;

class EmailUserExistValidator extends ConstraintValidator
{

    public function __construct(private UserRepository $userRepository)
    {

    }

    public function validate($value, Constraint $constraint): void
    {
        $email = $this->context->getValue();
        $user = $this->userRepository->findOneBy([
            'email' => $email
        ]);

        if($user !== null){
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ title }}', $email)
            ->atPath('id')
            ->addViolation();
        } 
    }
}