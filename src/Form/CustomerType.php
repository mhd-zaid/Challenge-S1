<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use App\Validator\CustomerEmail;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id')
            ->add('firstname')
            ->add('lastname')
            ->add('email', EmailType::class, [
                'constraints' => [new CustomerEmail]
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'plainPassword',
            ])
            ->add('validatedPassword', TextType::class, [
                'mapped' => false,
                "label" => 'Validate Password'
            ])
            ->add('address')
            ->add('phone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
