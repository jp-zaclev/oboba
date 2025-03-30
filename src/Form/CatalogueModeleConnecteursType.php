<?php
namespace App\Form;

use App\Entity\CatalogueModeleConnecteurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class CatalogueModeleConnecteursType extends AbstractType
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
            ->add('type', TextType::class, [
                'label' => 'Type',
                'constraints' => [
                    new NotBlank(['message' => 'Le type est requis']),
                ],
            ])
            ->add('nombreContacts', IntegerType::class, [
                'label' => 'Nombre de contacts',
                'constraints' => [
                    new NotBlank(['message' => 'Le nombre de contacts est requis']),
                    new PositiveOrZero(['message' => 'Le nombre de contacts doit être positif ou zéro']),
                ],
            ])
            ->add('prixUnitaire', NumberType::class, [
                'label' => 'Prix unitaire',
                'scale' => 2,
                'attr' => ['step' => '0.01'],
                'constraints' => [
                    new NotBlank(['message' => 'Le prix unitaire est requis']),
                    new PositiveOrZero(['message' => 'Le prix unitaire doit être positif ou zéro']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CatalogueModeleConnecteurs::class,
        ]);
    }
}
