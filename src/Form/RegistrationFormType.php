<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //gestion ajout choix Admin pour AdminController
        $adminChoice = $options['adminChoice'];

        //choix de rôle pour le formulaire
        $rolesChoices = [
            'Client' => 'client',
            'Patissier' => 'pastryChef',
        ];

        // Si adminChoice est true, ajout 'Admin' 
        if ($adminChoice) {
            $rolesChoices['Admin'] = 'admin';
        }

        $builder
            ->add('roles', ChoiceType::class, [
                'choices' => $rolesChoices,
                'mapped' => false,
                'required' => false,
                'placeholder' => 'Sélectionnez ici',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner un rôle.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class)
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les termes.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 30,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'adminChoice' => false,
        ]);
    }
}
