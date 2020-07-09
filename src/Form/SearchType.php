<?php

namespace App\Form;

use App\Entity\Campus;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', Entity::class, [
                'label'     => "Campus",
                'class'     => Campus::class
            ])
            ->add('keywords', TextType::class, [
                'label'     => 'Le nom de la sortie contient :'
            ])
            ->add('startDate', DateType::class, [
                'label'     => 'Entre'
            ])
            ->add('endDate', DateType::class, [
                'label'     => 'et'
            ])
            ->add('organised', CheckboxType::class, [
                'label'     => 'Sorties dont je suis l\'organisatrice/teur'
            ])
            ->add('signedIn', CheckboxType::class, [
                'label'     => 'Sorties auxquelles je suis inscrit/e'
            ])
            ->add('notSignedIn', CheckboxType::class, [
                'label'     => 'Sorties auxquelles je ne suis pas inscrit/e'
            ])
            ->add('passed', CheckboxType::class, [
                'label'     => 'Sorties passées'
            ])
            ->add('submit', SubmitType::class, [
                'label'     => 'Rechercher'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
