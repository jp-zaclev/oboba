<?php
// src/Form/CatalogueConducteurType.php
namespace App\Form;

use App\Entity\CatalogueConducteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CatalogueConducteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('attribut', TextType::class, [
                'label' => 'Attribut du conducteur',
                'constraints' => [new NotBlank(['message' => 'L’attribut est requis'])],
                'attr' => ['placeholder' => 'Ex: Rouge, 1, A'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CatalogueConducteur::class,
        ]);
    }
}
