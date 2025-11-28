<?php

// src/Form/PreferenceType.php
namespace App\Form;

use App\Entity\User;
use App\Entity\Objective;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PreferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Entrez votre ville']
            ])
            ->add('favoriteKeywords', TextareaType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Vos mots-clés favoris']
            ])
            ->add('objectives', CollectionType::class, [
                'entry_type' => ObjectiveType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}