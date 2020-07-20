<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\City;
use App\Repository\PlaceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifyEventType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $eventCity = $options['eventCity'];
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie'
            ])
            ->add('startDateTime', DateTimeType::class, [
                'label'     => 'Date et heure de la sortie',
                'required'  => true,
                'widget'    => 'single_text'
            ])
            ->add('inscriptionLimit', DateType::class, [
                'label'=> "Date limite d'inscription",
                'required'  => true,
                'widget'    => 'single_text',
            ])
            ->add('maxParticipant', NumberType::class, [
                'label' => 'Nombre de place'
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Durée en minutes',
                'attr' => [
                    'placeholder' => 90,
                    'min' => 0
                ],
            ])
            ->add('eventInfo', TextareaType::class, [
                'label' => 'Description et infos'
            ])
            ->add('campus', EntityType::class, [
                'label'=>'Campus',
                'class'=> Campus::class,
                'choice_label'=>'name',
                'disabled'=>true,
            ])
            ->add('place', null, [
                'label' => 'Lieu',
                'choice_label' => 'name',
                'query_builder' => function(PlaceRepository $placeRepository) use ($eventCity) {
                    return $placeRepository->createQueryBuilder('p')
                        ->andWhere('p.city = :param')
                        ->setParameter('param' , $eventCity);
                }
            ])

            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('saveAndAdd', SubmitType::class, ['label' => 'Publier la sortie'])
            ->add('delete', SubmitType::class, ['label'=>'Supprimer la sortie'])
            ->getForm();
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
        $resolver->setRequired([
            'eventCity',
        ]);
    }
}
