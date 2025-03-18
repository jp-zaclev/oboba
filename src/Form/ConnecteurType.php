<?php
namespace App\Form;

use App\Entity\Connecteur;
use App\Entity\CatalogueProjetConnecteurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConnecteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du connecteur',
                'constraints' => [new NotBlank(['message' => 'Le nom est requis'])]
            ])
            ->add('catalogueProjetConnecteurs', EntityType::class, [
                'class' => CatalogueProjetConnecteurs::class,
                'choice_label' => 'nom',
                'label' => 'Catalogue Projet',
                'required' => false,
                'placeholder' => 'Choisir une référence',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('c')
                        ->where('c.idProjet = :projetId')
                        ->setParameter('projetId', $options['projet_id']);
                },
                'constraints' => [new NotBlank(['message' => 'Vous devez sélectionner une référence'])]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Connecteur::class,
            'projet_id' => null,
        ]);
        $resolver->setRequired('projet_id');
    }
}
