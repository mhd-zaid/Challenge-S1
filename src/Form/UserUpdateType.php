<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Validator\EmailExist;

class UserUpdateType extends AbstractType
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
                'constraints' => [new EmailExist]
            ])

            ->add('roles', CollectionType::class, [
                'label' => 'Roles',
                'entry_type' => ChoiceType::class,
                'entry_options' => [
                    'label' => false,
                    'choices'  => [
                        'ROLE_ADMIN' => 'ROLE_ADMIN',
                        'ROLE_CUSTOMER' => 'ROLE_CUSTOMER',
                        'ROLE_MECHANIC' => 'ROLE_MECHANIC',
                        'ROLE_ACCOUNTANT' => 'ROLE_ACCOUNTANT',
                    ],
                ]
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
