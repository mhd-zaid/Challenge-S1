<?php

namespace App\Form;

use App\Entity\Prestation;
use App\Validator\ProductQuantity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;

class PrestationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('category', EntityType::class, [
                'label' => 'Category',
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('duration',IntegerType::class)
            ->add('workforce', NumberType::class, [
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prestation::class,
        ]);
    }
}
