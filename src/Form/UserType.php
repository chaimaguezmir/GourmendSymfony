<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class, [
    'constraints' => [
        new Assert\NotBlank(), // Le champ ne doit pas être vide
        new Assert\Email(),    // Le champ doit être une adresse email valide
    ],])
            ->add('roles',ChoiceType::class,[
                    'expanded' => false,
                    'multiple' =>  false,

                    'choices'  => [
                        'Admin' => 'ROLE_ADMIN',
                        'Client' => 'ROLE_USER',
                        
                    ],


                ]
            )
            ->add('password',PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Assert\NotNull(['message' => 'Veuillez renseigner ce champ.']),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}/',
                        'message' => 'Votre mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule et un chiffre.',
                    ]),
                ],
            ])
            ->add('name',TextType::class, [
    'constraints' => [
        new Assert\NotBlank(),
        new Assert\Length(['min' => 2, 'max' => 255]),
    ],
])
            ->add('age',IntegerType::class, [
    'constraints' => [
        new Assert\NotBlank(),
        new Assert\Range(['min' => 18, 'max' => 100]), // Exemple : âge doit être entre 18 et 100
    ],
])
            ->add('prenom',TextType::class, [
    'constraints' => [
        new Assert\NotBlank(),
        new Assert\Length(['min' => 2, 'max' => 255]),
    ],
])
            ->add('num_tel')
            ->add('adresse')
            ->add('token')
                  ->add('image',FileType::class, [
        'label' => 'picture for your  profile (image file)',

        // unmapped means that this field is not associated to any entity property
        'mapped' => false,

        // make it optional so you don't have to re-upload the PDF file
        // every time you edit the Product details
        'required' => false,


        // unmapped fields can't define their validation using attributes
        // in the associated entity, so you can use the PHP constraint classes
        'constraints' => [
            new File([
                'maxSize' => '1024k',
                'mimeTypes' => [
                    'image/gif',
                    'image/jpeg',
                    'image/png',
                    'image/jpg',
                ],
                'mimeTypesMessage' => 'Please upload a valid image',
            ])
        ]])
            ->add('Ajouter',SubmitType::class,[
                'label' => 'Save',
            ])
        ;
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                fn ($rolesAsArray) => count($rolesAsArray) ? $rolesAsArray[0]: null,
                fn ($rolesAsString) => [$rolesAsString]
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
