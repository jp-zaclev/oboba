<?php
namespace App\Form;

use App\Entity\Bornier;
use App\Entity\CatalogueProjetBorniers;
use App\Entity\Localisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;

class BornierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du bornier',
                'constraints' => [new NotBlank(['message' => 'Le nom est requis'])]
            ])
            ->add('localisation', EntityType::class, [
                'class' => Localisation::class,
                'choice_label' => 'nom',
                'label' => 'Localisation',
                'required' => true,
                'placeholder' => 'Choisir une localisation',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('l')
                        ->where('l.projet = :projetId')
                        ->setParameter('projetId', $options['projet_id']);
                },
                'constraints' => [new NotBlank(['message' => 'Vous devez sélectionner une localisation'])]
            ])
            ->add('catalogueProjetBorniers', EntityType::class, [
                'class' => CatalogueProjetBorniers::class,
                'choice_label' => 'nom',
                'label' => 'Catalogue Projet',
                'required' => false,
                'placeholder' => 'Choisir une référence',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('c')
                        ->where('c.projet = :projetId')
                        ->setParameter('projetId', $options['projet_id']);
                },
                'constraints' => [new NotBlank(['message' => 'Vous devez sélectionner une référence'])]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bornier::class,
            'projet_id' => null,
        ]);
        $resolver->setRequired('projet_id');
    }
}
