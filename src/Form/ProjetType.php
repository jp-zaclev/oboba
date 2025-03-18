<?php
namespace App\Form;

use App\Entity\Projet;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'label' => 'Nom du projet',
            ])
            ->add('proprietaire', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'email', // Ou 'nom' si tu préfères afficher le nom
                'label' => 'Propriétaire',
                'placeholder' => 'Choisir un propriétaire',
                'required' => true,
            ])
            ->add('concepteurs', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'email',
                'label' => 'Concepteurs',
                'multiple' => true,
                'expanded' => true, // Cases à cocher
                'required' => false,
            ])
            ->add('lecteurs', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'email',
                'label' => 'Lecteurs',
                'multiple' => true,
                'expanded' => true, // Cases à cocher
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
