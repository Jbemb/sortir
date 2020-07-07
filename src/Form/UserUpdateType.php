<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label'=>'Pseudo'
            ])
            ->add('firstName', TextType::class, [
                'label'=>'Prénom'
            ])
            ->add('lastName', TextType::class, [
                'label'=>'Nom'
            ])
            ->add('telephone', TextType::class, [
                'label'=>'Télephone'
            ])
            ->add('email', EmailType::class, [
                'label'=>'Email'
            ])
            ->add('password', RepeatedType::class, [
                'type'=> PasswordType::class,
                'first_options'=>['label'=>'Mot de passe'],
                'second_options'=>['label'=>'Confirmation'],
                'required'=>true,
                ''
            ])

            ->add('campus', ChoiceType::class, [
                'label'=>'Campus'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
