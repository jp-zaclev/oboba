<?php
// src/Form/CatalogueModeleCablesFilterType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CatalogueModeleCablesFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => ['placeholder' => 'Filtrer par nom'],
            ])
            ->add('type', TextType::class, [
                'label' => 'Type',
                'required' => false,
                'attr' => ['placeholder' => 'Filtrer par type'],
            ])
            ->add('nombreConducteursMaxMin', NumberType::class, [
                'label' => 'Conducteurs min',
                'required' => false,
                'attr' => ['placeholder' => 'Min'],
            ])
            ->add('nombreConducteursMaxMax', NumberType::class, [
                'label' => 'Conducteurs max',
                'required' => false,
                'attr' => ['placeholder' => 'Max'],
            ])
            ->add('prixMetreMin', NumberType::class, [
                'label' => 'Prix min',
                'required' => false,
                'attr' => ['placeholder' => 'Min', 'step' => '0.01'],
            ])
            ->add('prixMetreMax', NumberType::class, [
                'label' => 'Prix max',
                'required' => false,
                'attr' => ['placeholder' => 'Max', 'step' => '0.01'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // Pas d'entité liée, juste des données de filtre
            'method' => 'GET',    // Utilise GET pour conserver les filtres dans l'URL
            'csrf_protection' => false, // Pas besoin de CSRF pour un filtre GET
        ]);
    }
}
