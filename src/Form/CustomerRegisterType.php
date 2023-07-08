<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use App\Validator\PasswordMatch;
use App\Validator\ClientId;
use App\Validator\ClientExist;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class CustomerRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {         
            $builder
                ->add('id', IntegerType::class, [
                    'constraints' => [new ClientId, new ClientExist]
                ])
                ->add('token', HiddenType::class, [
                    'mapped' => false,
                    'data' => $options['data']['token']
                ])
                ->add('plainPassword', PasswordType::class, [
                'constraints' => [new PasswordMatch]
                ])
                ->add('password', PasswordType::class, [
                    'mapped' => false,
                ])
        ;
    }
}
