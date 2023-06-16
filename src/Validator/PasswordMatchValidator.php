<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordMatchValidator extends ConstraintValidator
{

    public function __construct()
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        $password = $this->context->getValue();

        if($password !== $this->context->getRoot()->get('password')->getData()){
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ title }}', $password)
            ->atPath('password')
            ->addViolation();
        }        
    }
}