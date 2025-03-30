<?php
namespace App\Form;

use App\Entity\CatalogueBorne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CatalogueBorneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('attribut', TextType::class, [
                'label' => 'Identification',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: Vis 2.5mm²'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CatalogueBorne::class,
        ]);
    }
}
