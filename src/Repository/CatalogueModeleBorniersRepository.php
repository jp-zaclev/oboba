<?php
namespace App\Repository;

use App\Entity\CatalogueModeleBorniers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CatalogueModeleBorniers|null find($id, $lockMode = null, $lockVersion = null)
 * @method CatalogueModeleBorniers|null findOneBy(array $criteria, array $orderBy = null)
 * @method CatalogueModeleBorniers[]    findAll()
 * @method CatalogueModeleBorniers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatalogueModeleBorniersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CatalogueModeleBorniers::class);
    }

    // Exemple de méthode personnalisée (optionnelle, commentée) :
    /*
    public function findByNombreBornes(int $nombreBornes): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.nombreBornes = :nombreBornes')
            ->setParameter('nombreBornes', $nombreBornes)
            ->orderBy('b.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
    */
}
