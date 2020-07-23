<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminCreateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,
                ['label' => 'Pseudo'])
           // ->add('roles')
            ->add('password', TextType::class,
               ['label' => 'Mot de passe'])
            ->add('lastName', TextType::class,
            ['label' => 'Nom'])
            ->add('firstName', TextType::class,
                ['label' => 'Prénom'])
            ->add('telephone', TextType::class,
                ['label' => 'Téléphone'])
            ->add('email', EmailType::class, [
                'label'=>'Email'])
            ->add('isActive')
            ->add('campus')
            ->add('add', SubmitType::class,
                ['label' => 'Ajouter'])
            ->getForm();
           // ->add('events')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
