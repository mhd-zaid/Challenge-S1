<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;

class EmailExistValidator extends ConstraintValidator
{
    
    private $customerRepository;
    private $userRepository;

    public function __construct(CustomerRepository $customerRepository, UserRepository $userRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint): void
    {
        $email = $this->context->getValue();
        $user = $this->userRepository->findOneBy([
            'email' => $email
        ]);
        $customer = $this->customerRepository->findOneBy([
            'email' => $email
        ]);
        if($customer !== null || $user !== null){
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ title }}', $email)
            ->atPath('id')
            ->addViolation();
        } 
    }
}