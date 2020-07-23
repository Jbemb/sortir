<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Event;
use App\Form\Type\PlaceHiddenType;
use DateTime;
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
                'label'     => 'Date et heure de la sortie',
                'required'  => true,
                'widget'    => 'single_text',
                'attr'   => [
                    'min' => ( new DateTime() )->format('Y-m-d')
                ]
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Durée',
                'attr' => [
                    'placeholder' => 'en minutes',
                    'min' => 0
                ],
            ])
            ->add('city', EntityType::class, [
                'label' => 'Ville',
                'class' => City::class,
                'choice_label' => 'name',
                'mapped' => false,
                'placeholder' => 'Sélectionnez une ville',
                'choice_attr'  => function($val){
                    return ['data-postalCode' => $val->getPostalCode()];
                }
            ])
            ->add('place', PlaceHiddenType::class)

            ->add('maxParticipant', NumberType::class, [
                'label' => 'Nombre de place'
            ])
            ->add('eventInfo', TextareaType::class, [
                'label' => 'Description et infos'
            ])
            ->add('inscriptionLimit', DateType::class, [
                'label' => "Date limite d'inscription",
                'required'  => true,
                'widget'    => 'single_text',
                'attr'   => [
                    'min' => ( new DateTime() )->format('Y-m-d')
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('saveAndAdd', SubmitType::class, ['label' => 'Publier la sortie'])
            ->getForm();;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
