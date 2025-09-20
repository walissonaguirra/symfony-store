<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options: [
                'label' => 'Nome'
            ])
            ->add('description', options: [
                'label' => 'Descrição Rápida'
            ])
            ->add('bady', options: [
                'label' => 'Conteúdo'
            ])
            ->add('price', TextType::class, options: [
                'label' => 'Preço'
            ])
            ->add('photos', FileType::class, options: [
                'mapped' => false,
                'multiple' => true
            ])
            ->add('slug')
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'label' => 'Categorias',
                'choice_label' => 'name',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
