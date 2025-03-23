<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CatalogueModeleBorniersFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => ['placeholder' => 'Filtrer par nom'],
            ])
            ->add('nombreBornesMin', NumberType::class, [
                'label' => 'Bornes min',
                'required' => false,
                'attr' => ['placeholder' => 'Min'],
            ])
            ->add('nombreBornesMax', NumberType::class, [
                'label' => 'Bornes max',
                'required' => false,
                'attr' => ['placeholder' => 'Max'],
            ])
            ->add('caracteristiques', TextType::class, [
                'label' => 'Caractéristiques',
                'required' => false,
                'attr' => ['placeholder' => 'Filtrer par caractéristiques'],
            ])
            ->add('prixUnitaireMin', NumberType::class, [
                'label' => 'Prix min',
                'required' => false,
                'attr' => ['placeholder' => 'Min', 'step' => '0.01'],
            ])
            ->add('prixUnitaireMax', NumberType::class, [
                'label' => 'Prix max',
                'required' => false,
                'attr' => ['placeholder' => 'Max', 'step' => '0.01'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
