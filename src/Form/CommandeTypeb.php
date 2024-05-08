<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Panier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Choice;

class CommandeTypeb extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // ->add('date', DateType::class, [
        //     'constraints' => [
        //         new NotBlank(),
        //     ],
        // ])
        // ->add('adresse_dest', TextType::class, [
        //     'constraints' => [
        //         new NotBlank(),
        //         new Length(['min' => 5, 'max' => 255]),
        //     ],
        // ])
        // // ->add('prix_total', TextType::class, [
        // //     'constraints' => [
        // //         new NotBlank(),
                
        // //     ],
        // // ])
        // ->add('prix_total', NumberType::class, [
        //     'constraints' => [
        //         new NotBlank(),
        //         new Assert\GreaterThan(0),
        //         new Assert\Regex([
        //             'pattern' => '/^\d+(\.\d+)?$/',
        //             'message' => 'Le prix doit être un nombre à virgule flottante.'
        //         ]),
        //     ],
        // ])
        ->add('status', ChoiceType::class, [
            'choices' => [
                'Pending' => 'pending',
                'Completed' => 'completed',
                'Cancelled' => 'cancelled',
            ],
            'constraints' => [
                new NotBlank(),
                new Choice(['choices' => ['pending', 'completed', 'cancelled']]),
            ],
        ]);
            // ->add('idPersonne')
            // ->add('paniers', EntityType::class, [
            //     'class' => Panier::class,
            //     'choice_label' => function(Panier $panier) {
            //         return $panier->getId(); // Assurez-vous d'avoir une méthode getId() dans votre entité Panier
            //     },
            //     'expanded' => true, // Rend les checkboxes visibles
            //     'multiple' => true, // Permet la sélection de plusieurs entités
            // ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
