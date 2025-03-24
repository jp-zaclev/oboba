<?php
// src/Form/CatalogueModeleConnecteursType.php
namespace App\Form;

use App\Entity\CatalogueModeleConnecteurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class CatalogueModeleConnecteursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du modèle',
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est requis']),
                ],
                'attr' => ['placeholder' => 'Ex: Connecteur XLR 3 broches'],
            ])
            ->add('type', TextType::class, [
                'label' => 'Type',
                'constraints' => [
                    new NotBlank(['message' => 'Le type est requis']),
                ],
                'attr' => ['placeholder' => 'Ex: XLR'],
            ])
            ->add('nombreContacts', IntegerType::class, [
                'label' => 'Nombre de contacts',
                'constraints' => [
                    new NotBlank(['message' => 'Le nombre de contacts est requis']),
                    new Positive(['message' => 'Le nombre doit être positif']),
                ],
                'attr' => ['min' => 1],
            ])
            ->add('prixUnitaire', NumberType::class, [
                'label' => 'Prix unitaire',
                'scale' => 2,
                'constraints' => [
                    new NotBlank(['message' => 'Le prix est requis']),
                    new PositiveOrZero(['message' => 'Le prix doit être positif ou zéro']),
                ],
                'attr' => ['step' => '0.01'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CatalogueModeleConnecteurs::class,
        ]);
    }
}
