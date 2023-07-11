<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ProductRepository;

class ProductQuantityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
    ->add('product', EntityType::class, [
        'label' => 'Produit',
        'class' => Product::class,
        'choice_label' => 'title',
        'query_builder' => function (ProductRepository $er) {
            return $er->createQueryBuilder('c')
                ->where('c.isActive = :isActive')
                ->setParameter('isActive', true);
        },
    ])
    ->add('quantity', IntegerType::class, [
        'label' => 'Quantité',
        'constraints' => [
            new Assert\Positive([
                'message' => 'Veuille ajouter une quantité positive.',
            ]),
        ],
    ]);
}
}