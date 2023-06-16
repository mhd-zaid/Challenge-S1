<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Product;

class ProductQuantityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
    ->add('product', EntityType::class, [
        'label' => 'Produit',
        'class' => Product::class,
        'choice_label' => 'title',
    ])
    ->add('quantity', IntegerType::class, [
        'label' => 'Quantité'
    ]);
}
}