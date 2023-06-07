<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Lastname',
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Firstname',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'plainPassword',
            ])
            ->add('address', TextType::class, [
                'label' => 'Address',
            ])
            ->add('phone', TextType::class, [
                'label' => 'Phone',
            ])
            ->add('password', ChoiceType::class, [
                'label' => 'Roles',
                'choices'  => [
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_CUSTOMER' => 'ROLE_CUSTOMER',
                    'ROLE_MECHANIC' => 'ROLE_MECHANIC',
                    'ROLE_ACCOUNTANT' => 'ROLE_ACCOUNTANT',
                ],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
