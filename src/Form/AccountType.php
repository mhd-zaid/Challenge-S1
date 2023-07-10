<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\User;
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
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use App\Validator\CustomerEmail;
use App\Validator\PasswordMatch;
use App\Validator\ClientId;
use App\Validator\ClientExist;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email', EmailType::class, [
                // 'constraints' => [new CustomerEmail]
            ])
            ->add('currentPassword', PasswordType::class, [
                'mapped' => false,
                'constraints' => [new UserPassword(message: 'Please enter your current password to make any changes')],
            ])
            ->add('plainPassword', PasswordType::class, [
                'constraints' => [new PasswordMatch],
                'required' => false,
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'required' => false,
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
                ],
            ]);
            // ->add('theme', ChoiceType::class, [
            //     'label' => 'Thème',
            //     'choices'  => [
            //         'Clair' => 'light',
            //         'Sombre' => 'dark',
            //     ],
            // ])
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
//            'data_class' => User::class,
        ]);
    }
}
