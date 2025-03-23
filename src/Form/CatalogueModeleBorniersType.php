<?php
namespace App\Form;

use App\Entity\CatalogueModeleBorniers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class CatalogueModeleBorniersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du modèle',
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est requis']),
                ],
                'attr' => ['placeholder' => 'Ex: Bornier à vis 4P'],
            ])
            ->add('nombreBornes', IntegerType::class, [
                'label' => 'Nombre de bornes',
                'constraints' => [
                    new NotBlank(['message' => 'Le nombre de bornes est requis']),
                    new Positive(['message' => 'Le nombre doit être positif']),
                ],
                'attr' => ['min' => 1],
            ])
            ->add('caracteristiques', TextType::class, [
                'label' => 'Caractéristiques',
                'required' => false,
                'constraints' => [], // Pas de contrainte car nullable
                'attr' => ['placeholder' => 'Ex: 2.5mm² max'],
            ])
            ->add('prixUnitaire', NumberType::class, [
                'label' => 'Prix unitaire',
                'scale' => 2,
                'constraints' => [
                    new NotBlank(['message' => 'Le prix est requis']),
                    new PositiveOrZero(['message' => 'Le prix doit être positif ou zéro']),
                ],
                'attr' => ['step' => '0.01', 'placeholder' => 'Ex: 1.20'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CatalogueModeleBorniers::class,
        ]);
    }
}
