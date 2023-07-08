<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\Request;

class ClientIdValidator extends ConstraintValidator
{

    public function __construct(private CustomerRepository $customerRepository)
    {
    
    }

    public function validate($value, Constraint $constraint): void
    {
        $id = $this->context->getValue();
        $token = $this->context->getRoot()->getViewData()['token'];
        $customer = $this->customerRepository->findOneBy([
            'id' => $id,
            'validationToken' => $token
        ]);
        if($customer == null){
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ title }}', $id)
            ->atPath('id')
            ->addViolation();
        }        
    }
}