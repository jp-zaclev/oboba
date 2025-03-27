<?php
namespace App\Form;

use App\Entity\CatalogueProjetBorniers;
use App\Entity\Localisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BornierFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $projetId = $options['projet_id'];

        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => ['placeholder' => 'Filtrer par nom'],
            ])
            ->add('nombreBornes', NumberType::class, [
                'label' => 'Nombre de bornes',
                'required' => false,
                'attr' => ['placeholder' => 'Filtrer par nombre'],
            ])
            ->add('caracteristiques', TextType::class, [
                'label' => 'Caractéristiques',
                'required' => false,
                'attr' => ['placeholder' => 'Filtrer par caractéristiques'],
            ])
            ->add('catalogueProjetBorniers', EntityType::class, [
                'class' => CatalogueProjetBorniers::class,
                'choice_label' => 'nom',
                'label' => 'Catalogue',
                'required' => false,
                'placeholder' => 'Tous les catalogues',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($projetId) {
                    return $er->createQueryBuilder('c')
                        ->where('c.projet = :projetId')
                        ->setParameter('projetId', $projetId);
                },
            ])
            ->add('localisation', EntityType::class, [
                'class' => Localisation::class,
                'choice_label' => 'nom',
                'label' => 'Emplacement',
                'required' => false,
                'placeholder' => 'Tous les emplacements',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($projetId) {
                    return $er->createQueryBuilder('l')
                        ->where('l.projet = :projetId')
                        ->setParameter('projetId', $projetId)
                        ->orderBy('l.nom', 'ASC');
                },
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
