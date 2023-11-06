<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('displayName', TextType::class, [
                "label" => "Nom d'utilisateur",
            ])
            ->add('email')
            // ->add('roles',ChoiceType::class, [
            //     'mapped' => false,
            //     'label' => 'rôle',
            //     'choices' => [
            //         '' => '',
            //         'Admin' => 'ROLE_ADMIN',
            //         'Moderateur' => 'ROLE_MODERATEUR',
            //         'Utilisateur' => 'ROLE_USER',
            //     ],
            // ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe',
                        ]),
                        // new Length([
                        //     'min' => 6,
                        //     'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        //     // max length allowed by Symfony for security reasons
                        //     'max' => 4096,
                        // ]),
                        //Renforcer la sécurité des mots de passe dans Symfony en utilisant des expressions régulières (regex)
                        new Regex("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/","Il faut un mot de passe de 12 caractères avec 1 majuscule, 1 miniscule, 1 chiffre et un caractère spécial")
                    ],
                    'label' => 'Mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmez le mot de passe',
                ],
                'invalid_message' => 'The password fields must match.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
            ->add('isVerified')
            
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
