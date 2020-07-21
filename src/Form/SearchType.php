<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Search;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'label'     => 'Campus',
                'class'     => Campus::class,
                'choice_label'  => 'name'
            ])
            ->add('keywords', TextType::class, [
                'label'     => 'Le nom de la sortie contient :',
                'required'  => false
            ])
            ->add('startDate', DateType::class, [
                'label'     => 'Entre',
                'required'  => false,
                'widget'    => 'single_text',
            ])
            ->add('endDate', DateType::class, [
                'label'     => 'et',
                'required'  => false,
                'widget'    => 'single_text',
            ])
            ->add('isOrganiser', CheckboxType::class, [
                'label'     => 'Sorties dont je suis l\'organisatrice/teur',
                'required'  => false
            ])
            ->add('isSignedUp', CheckboxType::class, [
                'label'     => 'Sorties auxquelles je suis inscrit/e',
                'required'  => false
            ])
            ->add('isNotSignedUp', CheckboxType::class, [
                'label'     => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required'  => false
            ])
            ->add('isPassedEvent', CheckboxType::class, [
                'label'     => 'Sorties passées',
                'required'  => false
            ])
            ->add('submit', SubmitType::class, [
                'label'     => 'Rechercher'
            ])
            ->getForm()
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class'    => Search::class,
        ]);
    }
}
