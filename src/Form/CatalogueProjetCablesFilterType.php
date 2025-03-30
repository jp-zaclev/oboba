<?php
namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CatalogueProjetCablesFilterType extends AbstractCatalogueFilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('type', TextType::class, [
                'label' => 'Type',
                'required' => false,
                'attr' => ['placeholder' => 'Filtrer par type'],
            ])
            ->add('nbConducteurs', TextType::class, [
                'label' => 'Nombre de conducteurs',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: >10, <5, 10-20'],
            ]);
    }
}
