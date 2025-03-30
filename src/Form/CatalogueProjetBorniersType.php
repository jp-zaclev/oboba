<?php
namespace App\Form;

use App\Entity\CatalogueProjetBorniers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class CatalogueProjetBorniersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est requis']),
                ],
            ])
            ->add('nombreBornes', IntegerType::class, [
                'label' => 'Nombre de bornes',
                'constraints' => [
                    new NotBlank(['message' => 'Le nombre de bornes est requis']),
                    new PositiveOrZero(['message' => 'Le nombre de bornes doit être positif ou zéro']),
                ],
            ])
            ->add('caracteristiques', TextType::class, [
                'label' => 'Caractéristiques',
                'required' => false,
            ])
            ->add('prixUnitaire', NumberType::class, [
                'label' => 'Prix unitaire',
                'scale' => 2,
                'attr' => ['step' => '0.01'],
                'constraints' => [
                    new NotBlank(['message' => 'Le prix unitaire est requis']),
                    new PositiveOrZero(['message' => 'Le prix unitaire doit être positif ou zéro']),
                ],
            ])
            ->add('catalogueBornes', CollectionType::class, [
                'label' => 'Bornes',
                'entry_type' => CatalogueBorneType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CatalogueProjetBorniers::class,
        ]);
    }
}
