<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class ProjetRecrutementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('utilisateurs', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => Utilisateur::class,
                    'choice_label' => 'nom',
                    'label' => false, // Pas de label individuel
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Utilisateurs à recruter',
            ])
            ->add('roles', CollectionType::class, [
                'entry_type' => ChoiceType::class,
                'entry_options' => [
                    'choices' => [
                        'Lecteur' => 'lecteur',
                        'Concepteur' => 'concepteur',
                        'Propriétaire' => 'proprietaire',
                    ],
                    'label' => false, // Pas de label individuel
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Rôles',
            ]);
    }
}
