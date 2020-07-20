<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
                'label' => 'Durée en minutes',
                'attr' => [
                    'placeholder' => 90,
                    'min' => 0
                ],
            ])
            ->add('city', EntityType::class, [
                'label' => 'Ville',
                'class' => City::class,
                'choice_label' => 'name',
                'mapped' => false,
                'placeholder' => 'Sélectionnez une ville'
            ])

//            ->add('place', ChoiceType::class, [
////                'mapped' => false,
//                'label' => 'Lieu',
//                'class' => Place::class
////                'choices'=>[]
//////                'class' => Place::class,
//////              //  'choice_label' => 'name'
//            ])
            ->add('place', PlaceHiddenType::class)
            //se baser sur ce code pr js/ajax
            //<div class="form-group"><label class="required" for="event_place">Lieu</label><select id="event_place" name="event[place]" class="form-control"><option value="10">plage</option><option value="11">bar</option><option value="12">bowling</option><option value="13">ballade</option><option value="14">chez Lulu</option><option value="15">Réu CDA</option><option value="16">quartier du coin</option><option value="17">au bar de la rue qui tourne</option></select></div>

            ->add('maxParticipant', NumberType::class, [
                'label' => 'Nombre de place'
            ])
            ->add('eventInfo', TextareaType::class, [
                'label' => 'Description et infos'
            ])
            ->add('inscriptionLimit', DateType::class, [
                'label' => "Date limite d'inscription"
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
