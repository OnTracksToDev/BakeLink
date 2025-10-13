<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir votre adresse email.']),
                    new Email(['message' => 'Veuillez saisir une adresse email valide.']),
                ]
            ])
            ->add('password', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir votre mot de passe.']),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères.',
                    ]),
                ]
            ])
            ->add('firstname', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir votre prénom.']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Votre prénom doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Votre prénom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ]
            ])
            ->add('lastname', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir votre nom de famille.']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Votre nom de famille doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Votre nom de famille ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ]
            ])
            ->add('pseudo', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir votre pseudo.']),
                    new Length([
                        'min' => 3,
                        'max' => 20,
                        'minMessage' => 'Votre pseudo doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Votre pseudo ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ]
            ])
            ->add('phone')
            ->add('city', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir le nom de votre ville.']),
                ]
            ])
            ->add('createdAt', DateTimeType::class)
            ->add('socialLink')
            ->add('photoUrl', FileType::class, [
                'label' => 'photoUrl',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k', // Limite la taille du fichier à 1 Mo
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image au format JPEG, PNG ou GIF',
                    ])
                ]
            ])
            ->add('isVerified');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
