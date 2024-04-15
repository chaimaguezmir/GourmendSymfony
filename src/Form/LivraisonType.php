<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Livraison;
use App\Repository\CommandeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LivraisonType extends AbstractType
{
    private $commandeRepository;

    public function __construct(CommandeRepository $commandeRepository)
    {
        $this->commandeRepository = $commandeRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $livraison = $builder->getData(); // Récupérer l'entité Livraison associée au formulaire

        $builder
            ->add('adresse_depart', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 5, 'max' => 255]),
                ],
            ])
            ->add('adresse_arrive', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 5, 'max' => 255]),
                ],
            ])
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Accepter' => 'Accepter',
                    'refuser' => 'refuser',
                ],
            ])
            ->add('personneId')
            ->add('commandes', EntityType::class, [
                'class' => Commande::class,
                'choices' => $this->getCommandesWithLivraison($livraison), // Liste des commandes associées à la livraison et celles sans livraison
                'choice_label' => 'id', // Assurez-vous d'avoir une méthode getId() dans votre entité Commande
                'multiple' => true, // Permet la sélection de plusieurs commandes
                'expanded' => true, // Affiche les options comme des checkboxes
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }

    private function getCommandesWithLivraison(Livraison $livraison)
    {
        // Liste des commandes associées à la livraison et celles sans livraison
        return $this->commandeRepository->findCommandesWithLivraison($livraison);
    }
}
