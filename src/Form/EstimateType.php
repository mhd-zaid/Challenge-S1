<?php

namespace App\Form;

use App\Entity\Estimate;
use App\Entity\EstimatePrestation;
use App\Entity\Prestation;
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
use Doctrine\ORM\Mapping\Entity;

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
            ])
            ->add('validityDate', DateType::class, [
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