<?php
namespace App\Form;

use App\Entity\WireSignal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WireSignalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du signal',
                'attr' => [
                    'placeholder' => 'Ex: SIG_TEMP_001',
                    'maxlength' => 50, // Conforme à la longueur max de l’entité
                ],
                'help' => 'Le nom doit être unique (max 50 caractères).',
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Analogique' => 'analogique',
                    'Digital' => 'digital',
                ],
                'placeholder' => 'Sélectionnez un type',
                'required' => true,
                'help' => 'Choisissez entre analogique ou digital.',
            ])
            ->add('details', TextType::class, [
                'label' => 'Détails',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Description du signal',
                    'maxlength' => 255, // Conforme à la longueur max de l’entité
                ],
                'help' => 'Description optionnelle (max 255 caractères).',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WireSignal::class,
        ]);
    }
}
