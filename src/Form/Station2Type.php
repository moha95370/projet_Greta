<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Place;
use App\Entity\Station;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class Station2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            //->add('slug')
            ->add('address')
            ->add('latitude')
            ->add('longitude')
            //->add('type')
            //->add('place')
            ->add('type', EntityType::class, [
                'class' => Type::class
            ])
            ->add('place', EntityType::class, [
                'class' => Place::class
            ])
            ->add('active')
            
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Station::class,
        ]);
    }
}
