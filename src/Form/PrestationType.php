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
class PrestationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('category')
            ->add('duration',IntegerType::class)
            ->add('workforce', IntegerType::class, [
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
