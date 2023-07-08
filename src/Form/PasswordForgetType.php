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
use App\Validator\EmailNotExist;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordForgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {         
            $builder
                ->add('email', EmailType::class, [
                    'label' => 'Email',
                    'constraints' => [new EmailNotExist]
                ]);
    }
}