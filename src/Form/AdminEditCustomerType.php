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

class AdminEditCustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {         
            $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email', EmailType::class, [
                // 'constraints' => [new CustomerEmail]
            ])
            ->add('address')
            ->add('phone')
            ->add('city')
            ->add('zipCode')
            ->add('country')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
