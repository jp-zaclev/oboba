<?php
namespace App\Form;

use App\Entity\Cable;
use App\Entity\CatalogueProjetCables;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\NotBlank;


class CableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $projetId = $options['projet_id'];

        $builder
            ->add('nom')
            ->add('longueur')
            ->add('catalogueProjetCables', EntityType::class, [
                'class' => CatalogueProjetCables::class,
                'choice_label' => 'nom',
                'label' => 'Catalogue Projet',
                'required' => false,
                'placeholder' => 'Choisir une référence',
                'query_builder' => function (EntityRepository $er) use ($projetId) {
                    return $er->createQueryBuilder('c')
                        ->where('c.idProjet = :projet')
                        ->setParameter('projet', $projetId);
                },
               'constraints' => [new NotBlank(['message' => 'Vous devez sélectionner une référence'])]

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('projet_id');
        $resolver->setDefaults([
            'data_class' => Cable::class,
        ]);
    }
}
