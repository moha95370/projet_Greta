<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Charge;
use App\Entity\Station;
use App\Entity\Vehicle;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChargeType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('createdAt')
            ->add('duration')
            ->add('price')
            ->add('station', EntityType::class, [
                'class' => Station::class,
                "label" => "Station",
                'multiple' => true,
                'expanded' => false,
                'required' => true,
            ])
            ->add('vehicle', EntityType::class, [
                'class' => Vehicle::class,
                "label" => "Vehicule",
                'multiple' => true,
                'expanded' => false,
                'required' => true,
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Charge::class,
        ]);
    }
}
