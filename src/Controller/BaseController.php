<?php

namespace App\Controller;

use App\Entity\Projet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    protected function checkProjectAccess(Projet $projet, EntityManagerInterface $em): void
    {
        $utilisateur = $this->getUser();
        if (!$utilisateur) {
            throw $this->createAccessDeniedException('Vous devez être connecté.');
        }

        $qb = $em->createQueryBuilder();
        $isAssociated = $qb->select('COUNT(pu.id)')
            ->from('App\Entity\ProjetUtilisateur', 'pu')
            ->where('pu.projet = :projet')
            ->andWhere('pu.utilisateur = :utilisateur')
            ->setParameter('projet', $projet)
            ->setParameter('utilisateur', $utilisateur)
            ->getQuery()
            ->getSingleScalarResult() > 0;

        if (!$isAssociated) {
            $projetUtilisateurs = $projet->getProjetUtilisateurs();
            if ($projetUtilisateurs instanceof \Doctrine\ORM\PersistentCollection && !$projetUtilisateurs->isInitialized()) {
                $projetUtilisateurs->initialize();
            }
            $userId = $utilisateur->getId();
            $roles = $projetUtilisateurs->map(fn($pu) => $pu->getRole() . ' (ID: ' . ($pu->getUtilisateur() ? $pu->getUtilisateur()->getId() : 'null') . ')')->toArray();
            throw $this->createAccessDeniedException("Vous n’avez pas accès à ce projet. Votre ID: $userId. Associations trouvées: " . implode(', ', $roles));
        }
    }
}
