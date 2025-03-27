<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LocalisationFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => ['placeholder' => 'Filtrer par nom'],
            ])
            ->add('x', TextType::class, [
                'label' => 'X',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: <12, >3.5, 3<7'],
            ])
            ->add('y', TextType::class, [
                'label' => 'Y',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: <12, >3.5, 3<7'],
            ])
            ->add('z', TextType::class, [
                'label' => 'Z',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: <12, >3.5, 3<7'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
            'projet_id' => null,
        ]);
        $resolver->setRequired('projet_id');
    }
}
