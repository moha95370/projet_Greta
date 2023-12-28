<?php

namespace App\Form;

use App\Entity\UserRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
            ->add('email')
            ->add('phone')
            ->add('content')
            // ->add('active')
            // ->add('user')
            // ->add('typeRequest')
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
