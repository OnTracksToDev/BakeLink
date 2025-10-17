<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\PastryChef;
use App\Entity\RequestOrder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RequestOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'En attente',
                    'En cours' => 'En cours',
                    'Terminé' => 'Terminé',
                    'Annulé' => 'Annulé',
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
                'label' => 'Statut',
                'data' => 'En attente' // Valeur par défaut
            ])->add('eventDate', null, [
                'widget' => 'single_text',
            ])
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('finishedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'id',
            ])
            ->add('pastryChef', EntityType::class, [
                'class' => PastryChef::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RequestOrder::class,
        ]);
    }
}
