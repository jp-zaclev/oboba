<?php
namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CatalogueModeleConnecteursFilterType extends AbstractCatalogueFilterType
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
            ->add('nombreContacts', TextType::class, [
                'label' => 'Nombre de contacts',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: >4, <10, 2-6'],
            ]);
    }
}
