<?php
namespace App\Form;

use App\Entity\CatalogueProjetConnecteurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class CatalogueProjetConnecteursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est requis']),
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('type', TextType::class, [
                'label' => 'Type',
                'constraints' => [
                    new NotBlank(['message' => 'Le type est requis']),
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('nombreContacts', IntegerType::class, [
                'label' => 'Nombre de contacts',
                'constraints' => [
                    new NotBlank(['message' => 'Le nombre de contacts est requis']),
                    new PositiveOrZero(['message' => 'Le nombre de contacts doit être positif ou zéro']),
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('prixUnitaire', NumberType::class, [
                'label' => 'Prix unitaire',
                'scale' => 2,
                'attr' => ['step' => '0.01', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Le prix unitaire est requis']),
                    new PositiveOrZero(['message' => 'Le prix unitaire doit être positif ou zéro']),
                ],
            ])
            ->add('catalogueContacts', CollectionType::class, [
                'entry_type' => CatalogueContactType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Contacts',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CatalogueProjetConnecteurs::class,
        ]);
    }
}
