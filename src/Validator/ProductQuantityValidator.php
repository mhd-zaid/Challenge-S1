<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\ProductRepository;

class ProductQuantityValidator extends ConstraintValidator
{
    
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function validate($value, Constraint $constraint): void
    {
        $productQuantities = $this->context->getValue();
        foreach($productQuantities as $productQuantity){
            if ($productQuantity['quantity'] > $productQuantity['product']->getQuantity()) {
                // dump($this->context);die;
                $this->context->buildViolation($constraint->message)
                ->setParameter('{{ title }}', $productQuantity['product']->getTitle())
                ->atPath('productQuantities')
                ->addViolation();
            }
        }
        
    }
}