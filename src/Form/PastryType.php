<?php

namespace App\Form;

use App\Entity\Pastry;
use App\Entity\Category;
use App\Entity\PastryChef;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class PastryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //gestion required method 'edit' pour champs'photoUrl
        $isEdit = $options['is_edit'];
        $constraints = [
            new File([
                'maxSize' => '5120k', // Limite la taille du fichier à 1 Mo
                'mimeTypes' => [
                    'image/jpeg',
                    'image/png',
                    'image/gif',
                ],
                'mimeTypesMessage' => 'Veuillez télécharger une image au format JPEG, PNG ou GIF',
            ])
        ];
        if (!$isEdit) {
            $constraints[] = new NotBlank(['message' => 'La photo est requise']);
        }

        $builder
            ->add('photoUrl', FileType::class, [
                'label' => 'photoUrl',
                'mapped' => false,
                'required' => false,
                'constraints' => $constraints,
            ])
            ->add('title')
            ->add('description', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'La description est requise.',
                    ]),
                ],
            ])
            ->add('createdAt', DateTimeType::class)
            ->add('updatedAt', DateTimeType::class)
            ->add('pastryChef', EntityType::class, [
                'class' => PastryChef::class,
                'choice_label' => 'id',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'id',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pastry::class,
            'is_edit' => false,
        ]);
    }
}
