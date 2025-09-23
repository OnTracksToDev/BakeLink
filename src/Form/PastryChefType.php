<?php

namespace App\Form;

use App\Entity\PastryChef;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PastryChefType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('firstname')
            ->add('lastname')
            ->add('pseudo')
            ->add('phone')
            ->add('city')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('socialLink')
            ->add('photoUrl')
            ->add('isVerified')
            ->add('isProfileCompleted')
            ->add('experience')
            ->add('price')
            ->add('speciality')
            ->add('websiteLink')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PastryChef::class,
        ]);
    }
}
