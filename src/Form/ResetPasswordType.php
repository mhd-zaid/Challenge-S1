<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use App\Validator\PasswordMatch;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {         
            $builder
            ->add('plainPassword', PasswordType::class, [
                'constraints' => [new PasswordMatch]
                ])
                ->add('password', PasswordType::class, [
                    'mapped' => false,
                ]);
    }
}