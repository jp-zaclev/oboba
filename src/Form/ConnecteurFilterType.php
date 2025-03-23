<?php
// src/Form/ConnecteurFilterType.php
namespace App\Form;

use App\Entity\CatalogueProjetConnecteurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ConnecteurFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => ['placeholder' => 'Filtrer par nom']
            ])
            ->add('nombreContacts', IntegerType::class, [
                'label' => 'Nombre de contacts',
                'required' => false,
                'attr' => ['placeholder' => 'Filtrer par nombre de contacts']
            ])
            ->add('type', TextType::class, [
                'label' => 'Type',
                'required' => false,
                'attr' => ['placeholder' => 'Filtrer par type']
            ])
            ->add('catalogueProjetConnecteurs', EntityType::class, [
                'class' => CatalogueProjetConnecteurs::class,
                'choice_label' => 'nom',
                'label' => 'Catalogue',
                'required' => false,
                'placeholder' => 'Tous les catalogues',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('c')
                        ->where('c.projet = :projet')  // Paramètre :projet
                        ->setParameter('projet', $options['projet_id']);  // Défini comme :projet
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'projet_id' => null,
            'method' => 'GET',
        ]);
        $resolver->setRequired('projet_id');
    }
}
