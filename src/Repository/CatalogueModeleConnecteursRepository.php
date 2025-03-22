<?php 
// src/Repository/CatalogueModeleConnecteursRepository.php
namespace App\Repository;

use App\Entity\CatalogueModeleConnecteurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CatalogueModeleConnecteurs|null find($id, $lockMode = null, $lockVersion = null)
 * @method CatalogueModeleConnecteurs|null findOneBy(array $criteria, array $orderBy = null)
 * @method CatalogueModeleConnecteurs[]    findAll()
 * @method CatalogueModeleConnecteurs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatalogueModeleConnecteursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CatalogueModeleConnecteurs::class);
    }

    // Exemple de méthode personnalisée (optionnelle, commentée) :
    /*
    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.type = :type')
            ->setParameter('type', $type)
            ->orderBy('c.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
    */
}
