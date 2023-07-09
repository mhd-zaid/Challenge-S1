<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\EqualTo;
use App\Validator\CustomerEmail;
use App\Validator\PasswordMatch;
use App\Validator\ClientId;
use App\Validator\ClientExist;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {         
            $builder
            ->add('id', IntegerType::class, [
//                'constraints' => [new ClientId, new ClientExist]
            ])
            ->add('firstname')
            ->add('lastname')
            ->add('email', EmailType::class, [
                // 'constraints' => [new CustomerEmail]
            ])
            ->add('plainPassword', PasswordType::class, [
                'constraints' => [new PasswordMatch]
                
            
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                ])
            ->add('address')
            ->add('phone')
            ->add('city')
            ->add('zipCode')
            ->add('country')
            ->add('language', ChoiceType::class, [
                'label' => 'Langue',
                'choices'  => [
                    'Français' => 'fr',
                    'English' => 'en',
                    'Deutch' => 'de',
                    'Italiano' => 'it',
                    'Espanõl' => 'es',
                ],
            ])
            ->add('theme', ChoiceType::class, [
                'label' => 'Thème',
                'choices'  => [
                    'Clair' => 'light',
                    'Sombre' => 'dark',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
