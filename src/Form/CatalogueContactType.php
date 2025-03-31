<?php
namespace App\Form;

use App\Entity\CatalogueContact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CatalogueContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('identifiant', TextType::class, [
                'label' => 'Identifiant',
                'attr' => ['class' => 'form-control w-25 me-2'],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Émission' => 'emission',
                    'Réception' => 'reception',
                    'Émission/Réception' => 'emission_reception',
                ],
                'attr' => ['class' => 'form-control w-50 me-2'], 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CatalogueContact::class,
        ]);
    }
}
