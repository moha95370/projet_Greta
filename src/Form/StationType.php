<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Place;
use App\Entity\Station;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "label" => "Nom",
            ])
            //->add('slug')
            ->add('address', TextType::class, [
                "label" => "Adresse",
            ])
            //->add('createdAt')
            //->add('user')
            ->add('type', EntityType::class, [
                'class' => Type::class,
                "label" => "Type d'installation"
            ])
            ->add('place', EntityType::class, [
                'class' => Place::class,
                "label" => "Lieu d'installation"
            ])
            //->add('active')
            
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
