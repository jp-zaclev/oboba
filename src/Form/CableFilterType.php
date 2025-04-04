<?php
namespace App\Form;

use App\Entity\CatalogueProjetCables;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CableFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => ['placeholder' => 'Filtrer par nom'],
            ])
            ->add('longueur', TextType::class, [
                'label' => 'Longueur (m)',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: <4, >8, <=32, >=10, 7<12, 5'],
            ])
            ->add('catalogueProjetCables', EntityType::class, [
                'class' => CatalogueProjetCables::class,
                'choice_label' => 'nom',
                'label' => 'Catalogue',
                'required' => false,
                'placeholder' => 'Tous les catalogues',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('c')
                        ->where('c.projet = :projet')
                        ->setParameter('projet', $options['projet_id']);
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'projet_id' => null,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
        $resolver->setRequired('projet_id');
    }
}
