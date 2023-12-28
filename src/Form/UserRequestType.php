<?php

namespace App\Form;

use App\Entity\TypeRequest;
use App\Entity\UserRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('displayName', TextType::class, [
                "label" => "Nom d'utilisateur",
            ])
            ->add('email', TextType::class, [
                "label" => "E-mail",
            ])
            ->add('phone', TextType::class, [
                "label" => "Téléphone",
            ])
            ->add('typeRequest', EntityType::class, [
                'class' => TypeRequest::class,
                "label" => "Type de demande"
            ])
            ->add('content')
            // ->add('active')
            // ->add('user')
            
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserRequest::class,
        ]);
    }
}
