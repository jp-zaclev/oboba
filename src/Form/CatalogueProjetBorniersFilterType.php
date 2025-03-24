<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class CatalogueProjetBorniersFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => false,
            ])
            ->add('nombreBornes', IntegerType::class, [
                'label' => 'Nombre de bornes',
                'required' => false,
            ])
            ->add('caracteristiques', TextType::class, [
                'label' => 'Caractéristiques',
                'required' => false,
            ])
            ->add('prixUnitaireMin', NumberType::class, [
                'label' => 'Prix unitaire min',
                'required' => false,
            ])
            ->add('prixUnitaireMax', NumberType::class, [
                'label' => 'Prix unitaire max',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
