<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Categorie;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prod_name')
            ->add('type')
            ->add('stock')
            ->add('price')
            ->add('status',ChoiceType::class
            ,array('choices'=>array(

                'Ajouter status'=>'vide',
            'Disponible' =>'Disponible',
            'Non Disponible'=>'Non Disponible'
            )))
            
            ->add('image',FileType::class,array("data_class"=>null))
            ->add('date')
            ->add('idCategorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'choice_value' => 'nom',
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
