<?php

namespace App\Form;

use App\Entity\ProductRating;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductRatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nbrratting', ChoiceType::class, [
            'choices' => [
                '1 étoile' => 1,
                '2 étoiles' => 2,
                '3 étoiles' => 3,
                '4 étoiles' => 4,
                '5 étoiles' => 5,
            ],
            'expanded' => true,
            'multiple' => false,
            'label' => 'Note',
            'attr' => [
                'class' => 'rating-stars', // Ajoutez des classes CSS pour le style des étoiles si nécessaire
            ],
        ]);
           // ->add('Product')
       // ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductRating::class,
        ]);
    }
}
