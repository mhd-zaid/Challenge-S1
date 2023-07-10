<?php

namespace App\Form;

use App\Entity\Estimate;
use App\Entity\Prestation;
use App\Validator\EmailUserExist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class EstimateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('lastname', TextType::class, [
                'mapped' => false,
                'label' => 'Lastname'
            ])
            ->add('firstname', TextType::class, [
                'mapped' => false,
                'label' => 'Firstname'
            ])
            ->add('email', EmailType::class, [
                'mapped' => false,
                'label' => 'Email',
                'constraints' => [
                    new EmailUserExist()
                ]
            ])
            ->add('estimatePrestations', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' =>  Prestation::class,
                    'choice_label' => 'name',
                    'label' => false,
                ],
                'allow_add' => true,
                'by_reference' => false,
                'attr' => [
                    'class' => '',
                ],
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Vous devez ajouter au moins une prestation à un devis.',
                    ]),
                ],
            ])
            ->add('validityDate', DateType::class, [
                'mapped' => false,
                'label' => "Date de fin de validité du contrat",
                'data' => new \DateTime("now"),
                'constraints' => [
                    new Assert\GreaterThan('today')
                ],
                'attr' => [
                    'class' => 'datepicker',
                ],
            ])
            ->add('carId', TextType::class, [
                'label' => 'Car ID'
            ])

            ->add('carBrand', TextType::class, [
                'label' => 'Car Brand'
            ])

            ->add('carModel', TextType::class, [
                'label' => 'Car Model'
            ])
            ->add('carType', ChoiceType::class, [
                'choices' => [
                    'Essence' => 'Essence',
                    'Diesel' => 'Diesel',
                    'Hybride' => 'Hybride',
                    'Electrique' => 'Electrique',
                ],
                'label' => 'Car Type'
            ]); 
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Estimate::class,
        ]);
    }
}