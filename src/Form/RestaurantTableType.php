<?php

namespace App\Form;

use App\Entity\RestaurantTable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RestaurantTableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('capacity')
            ->add('available' , ChoiceType::class, [
                'choices' => [
                    'Disponible' => 'Disponible',
                    'Non Disponible' => 'Non Disponible',
                    
                ],
            ])
            ->add('description')
            ->add('reservationId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RestaurantTable::class,
        ]);
    }
}
