<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\CustomerRepository;

class ClientIdValidator extends ConstraintValidator
{
    
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function validate($value, Constraint $constraint): void
    {
        $id = $this->context->getValue();
        $customer = $this->customerRepository->findOneBy([
            'id' => $id
        ]);
        if($customer == null){
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ title }}', $id)
            ->atPath('id')
            ->addViolation();
        }        
    }
}