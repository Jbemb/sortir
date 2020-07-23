<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminCreateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
           // ->add('roles')
            ->add('password')
            ->add('lastName')
            ->add('firstName')
            ->add('telephone')
            ->add('email')
            ->add('isActive')
            ->add('campus')
            ->add('add', SubmitType::class, ['label' => 'Ajouter'])
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
