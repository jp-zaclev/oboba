<?php
namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CatalogueModeleBorniersFilterType extends AbstractCatalogueFilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('nombreBornes', TextType::class, [
                'label' => 'Nombre de bornes',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: >4, <10, 2-6'],
            ])
            ->add('caracteristiques', TextType::class, [
                'label' => 'Caractéristiques',
                'required' => false,
                'attr' => ['placeholder' => 'Filtrer par caractéristiques'],
            ]);
    }
}
