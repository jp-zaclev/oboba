<?php
// src/Form/CatalogueModeleCablesType.php
namespace App\Form;

use App\Entity\CatalogueModeleCables;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class CatalogueModeleCablesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du modèle',
                'constraints' => [new NotBlank(['message' => 'Le nom est requis'])],
                'attr' => ['placeholder' => 'Ex: Câble coaxial RG58'],
            ])
            ->add('type', TextType::class, [
                'label' => 'Type',
                'constraints' => [new NotBlank(['message' => 'Le type est requis'])],
                'attr' => ['placeholder' => 'Ex: coaxial'],
            ])
            ->add('nombreConducteursMax', IntegerType::class, [
                'label' => 'Nombre maximal de conducteurs',
                'constraints' => [
                    new NotBlank(['message' => 'Le nombre de conducteurs est requis']),
                    new PositiveOrZero(['message' => 'Le nombre doit être positif ou zéro']),
                ],
                'attr' => ['min' => 0],
            ])
            ->add('prixMetre', NumberType::class, [
                'label' => 'Prix au mètre',
                'scale' => 2,
                'data' => '0.00', // Forcer la valeur par défaut à 0.00 dans le formulaire
                'constraints' => [
                    new NotBlank(['message' => 'Le prix est requis']),
                    new PositiveOrZero(['message' => 'Le prix doit être positif ou zéro']),
                ],
                'attr' => ['step' => '0.01', 'placeholder' => 'Ex: 2.50'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CatalogueModeleCables::class,
        ]);
    }
}
