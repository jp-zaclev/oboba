<?php
// src/Form/CatalogueModeleCablesType.php
namespace App\Form;

use App\Entity\CatalogueModeleCables;
use App\Entity\CatalogueConducteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class CatalogueModeleCablesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du modèle',
                'constraints' => [new NotBlank(['message' => 'Le nom est requis'])],
                'attr' => ['placeholder' => 'Ex: Câble coaxial RG58'],
            ])
            ->add('type', TextType::class, [
                'label' => 'Type',
                'constraints' => [new NotBlank(['message' => 'Le type est requis'])],
                'attr' => ['placeholder' => 'Ex: coaxial'],
            ])
            ->add('nbConducteurs', IntegerType::class, [
                'label' => 'Nombre de conducteurs',
                'constraints' => [
                    new NotBlank(['message' => 'Le nombre de conducteurs est requis']),
                    new PositiveOrZero(['message' => 'Le nombre doit être positif ou zéro']),
                ],
                'attr' => ['min' => 0],
            ])
            ->add('prixUnitaire', NumberType::class, [
                'label' => 'Prix au mètre',
                'scale' => 2,
                'constraints' => [
                    new NotBlank(['message' => 'Le prix est requis']),
                    new PositiveOrZero(['message' => 'Le prix doit être positif ou zéro']),
                ],
                'attr' => ['step' => '0.01', 'placeholder' => 'Ex: 2.50'],
            ])
            ->add('catalogueConducteurs', CollectionType::class, [
                'label' => 'Conducteurs',
                'entry_type' => CatalogueConducteurType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ]);

        // Événement PRE_SUBMIT pour pré-remplir les conducteurs si la collection est vide
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            $catalogue = $form->getData();

            // Vérifier si des conducteurs ont été ajoutés manuellement
            if (empty($data['catalogueConducteurs']) && isset($data['nbConducteurs'])) {
                $nbConducteurs = (int)$data['nbConducteurs'];
                if ($nbConducteurs > 0) {
                    $conducteurs = [];
                    for ($i = 1; $i <= $nbConducteurs; $i++) {
                        $conducteurs[] = ['attribut' => (string)$i];
                    }
                    $data['catalogueConducteurs'] = $conducteurs;
                    $event->setData($data);
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CatalogueModeleCables::class,
        ]);
    }
}
