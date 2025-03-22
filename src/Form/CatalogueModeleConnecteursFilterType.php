<?php
// src/Form/CatalogueModeleConnecteursFilterType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CatalogueModeleConnecteursFilterType extends AbstractType
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
            ->add('nombreContactsMin', NumberType::class, [
                'label' => 'Contacts min',
                'required' => false,
                'attr' => ['placeholder' => 'Min'],
            ])
            ->add('nombreContactsMax', NumberType::class, [
                'label' => 'Contacts max',
                'required' => false,
                'attr' => ['placeholder' => 'Max'],
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
