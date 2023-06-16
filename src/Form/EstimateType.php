<?php

namespace App\Form;

use App\Entity\Estimate;
use App\Entity\Product;
use App\Form\ProductQuantityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Validator\ProductQuantity;

class EstimateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('email', EmailType::class, [
                'mapped' => false,
                'label' => 'Email'
            ])
            ->add('workforce', IntegerType::class, [
                'mapped' => false,
                'label' => "Main d'oeuvre"
            ])
            ->add('productQuantities', CollectionType::class, [
                'mapped' => false,
                'entry_type' => ProductQuantityType::class,
                'allow_add' => true,
                'by_reference' => false,
                'attr' => [
                    'class' => 'product-quantities-container',
                ],
                'entry_options' => [
                    'label' => false,
                ],
                'constraints' => [new ProductQuantity]
            ])
            ->add('validity_date', DateType::class, [
                'mapped' => false,
                'label' => "Date de fin de validité du contrat"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Estimate::class,
        ]);
    }
}