<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;

class CustomerEmailValidator extends ConstraintValidator
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
        $customer = $this->customerRepository->findOneBy([
            'email' => $email
        ]);
        $user = $this->userRepository->findOneBy([
            'email' => $email
        ]);
        dump($customer);
        dump($user);

        if($user !== null || $customer !== null){
            dump('cii');
            die;
                $this->context->buildViolation($constraint->message)
                ->setParameter('{{ title }}', $email)
                ->atPath('email')
                ->addViolation();
        }        
    }
}