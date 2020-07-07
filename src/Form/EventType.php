<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Place;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie'
            ])
            ->add('startDateTime', DateTimeType::class, [
                'label' => 'Date et heure de la sortie'
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Durée',
                'attr' => ['placeholder' => 90],
                'attr' => ['min' => 0]
            ])
            ->add('place', EntityType::class, [
                'label' => 'Lieu',
                'class' => Place::class,
                'choice_label' => 'name'
            ])
            ->add('maxParticipant', NumberType::class, [
                'label' => 'Nombre de place'
            ])
            ->add('eventInfo', TextareaType::class, [
                'label' => 'Description et infos'
            ])
            ->add('inscriptionLimit', DateType::class, [
                'label'=> "Date limite d'inscription"
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('saveAndAdd', SubmitType::class, ['label' => 'Publier la sortie'])
            ->getForm();
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
