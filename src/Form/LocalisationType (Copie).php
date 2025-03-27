<?php
namespace App\Form;

use App\Entity\Localisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\NotBlank;

class LocalisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la localisation',
                'constraints' => [new NotBlank(['message' => 'Le nom est requis'])]
            ])
            ->add('x', NumberType::class, [
                'label' => 'Coordonnée X',
                'required' => false,
                'scale' => 2, // 2 décimales
            ])
            ->add('y', NumberType::class, [
                'label' => 'Coordonnée Y',
                'required' => false,
                'scale' => 2,
            ])
            ->add('z', NumberType::class, [
                'label' => 'Coordonnée Z',
                'required' => false,
                'scale' => 2,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Localisation::class,
        ]);
    }
}
