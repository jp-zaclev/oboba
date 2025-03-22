<?php
// src/Repository/CatalogueModeleCablesRepository.php
namespace App\Repository;

use App\Entity\CatalogueModeleCables;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CatalogueModeleCables|null find($id, $lockMode = null, $lockVersion = null)
 * @method CatalogueModeleCables|null findOneBy(array $criteria, array $orderBy = null)
 * @method CatalogueModeleCables[]    findAll()
 * @method CatalogueModeleCables[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatalogueModeleCablesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CatalogueModeleCables::class);
    }

    // Vous pouvez ajouter des méthodes personnalisées ici si besoin, par exemple :
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
