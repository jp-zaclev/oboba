<?php
namespace App\Controller;

use App\Entity\Connecteur;
use App\Form\ConnecteurType;
use App\Form\ConnecteurFilterType;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Knp\Component\Pager\PaginatorInterface;

class ConnecteurController extends AbstractController
{
    #[Route('/projet/{projetId}/connecteurs', name: 'connecteur_list', methods: ['GET'])]
    public function list(
        int $projetId,
        ProjetRepository $projetRepository,
        Request $request,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        PaginatorInterface $paginator
    ): Response {
        $projet = $projetRepository->find($projetId);
        if (!$projet) {
            throw $this->createNotFoundException('Projet non trouvé');
        }

        $this->denyAccessUnlessGranted('CAN_EDIT_CONNECTEURS', $projet);

        $filterForm = $formFactory->create(ConnecteurFilterType::class, null, ['projet_id' => $projetId]);
        $filterForm->handleRequest($request);

        $qb = $em->getRepository(Connecteur::class)->createQueryBuilder('c')
            ->leftJoin('c.catalogueProjetConnecteurs', 'cat') // Jointure existante
            ->addSelect('cat') // Ajout pour supporter le tri sur cat.*
            ->where('c.projet = :projet')
            ->setParameter('projet', $projet);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();

            if ($data['nom']) {
                $qb->andWhere('c.nom LIKE :nom')
                   ->setParameter('nom', '%' . $data['nom'] . '%');
            }
            if ($data['nombreContacts'] !== null) {
                $qb->andWhere('cat.nombreContacts = :nombreContacts')
                   ->setParameter('nombreContacts', $data['nombreContacts']);
            }
            if ($data['type']) {
                $qb->andWhere('cat.type LIKE :type')
                   ->setParameter('type', '%' . $data['type'] . '%');
            }
            if ($data['catalogueProjetConnecteurs']) {
                $qb->andWhere('c.catalogueProjetConnecteurs = :catalogue')
                   ->setParameter('catalogue', $data['catalogueProjetConnecteurs']);
            }
        }

        $connecteurs = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10,
            [
                'defaultSortFieldName' => 'c.nom', // Tri par défaut sur le nom
                'defaultSortDirection' => 'asc',
            ]
        );

        return $this->render('connecteur/list.html.twig', [
            'projet' => $projet,
            'connecteurs' => $connecteurs,
            'filter_form' => $filterForm->createView(),
        ]);
    }

    
    
    
    
    

    #[Route('/projet/{projetId}/connecteurs/new', name: 'connecteur_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        int $projetId,
        EntityManagerInterface $em,
        ProjetRepository $projetRepository,
        FormFactoryInterface $formFactory
    ): Response {
        $projet = $projetRepository->find($projetId);
        if (!$projet) {
            throw $this->createNotFoundException('Projet non trouvé');
        }

        $this->denyAccessUnlessGranted('CAN_EDIT_CONNECTEURS', $projet);

        $connecteur = new Connecteur();
        $connecteur->setProjet($projet);
        $form = $formFactory->create(ConnecteurType::class, $connecteur, ['projet_id' => $projetId]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($connecteur);
            $em->flush();
            $this->addFlash('success', 'Connecteur ajouté avec succès');
            return $this->redirectToRoute('connecteur_list', ['projetId' => $projetId]);
        }

        return $this->render('connecteur/new.html.twig', [
            'form' => $form->createView(),
            'projet' => $projet,
        ]);
    }

    #[Route('/projet/{projetId}/connecteurs/export', name: 'connecteur_export_csv', methods: ['GET'])]
    public function exportCsv(
        int $projetId,
        ProjetRepository $projetRepository,
        Request $request,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory
    ): Response {
        $projet = $projetRepository->find($projetId);
        if (!$projet) {
            throw $this->createNotFoundException('Projet non trouvé');
        }

        $this->denyAccessUnlessGranted('CAN_EDIT_CONNECTEURS', $projet);

        $filterForm = $formFactory->create(ConnecteurFilterType::class, null, ['projet_id' => $projetId]);
        $filterForm->handleRequest($request);

        $qb = $em->getRepository(Connecteur::class)->createQueryBuilder('c')
            ->leftJoin('c.catalogueProjetConnecteurs', 'cat')
            ->where('c.projet = :projet')
            ->setParameter('projet', $projet);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();

            if ($data['nom']) {
                $qb->andWhere('c.nom LIKE :nom')
                   ->setParameter('nom', '%' . $data['nom'] . '%');
            }
            if ($data['nombreContacts'] !== null) {
                $qb->andWhere('cat.nombreContacts = :nombreContacts')
                   ->setParameter('nombreContacts', $data['nombreContacts']);
            }
            if ($data['type']) {
                $qb->andWhere('cat.type LIKE :type')
                   ->setParameter('type', '%' . $data['type'] . '%');
            }
            if ($data['catalogueProjetConnecteurs']) {
                $qb->andWhere('c.catalogueProjetConnecteurs = :catalogue')
                   ->setParameter('catalogue', $data['catalogueProjetConnecteurs']);
            }
        }

        $connecteurs = $qb->getQuery()->getResult();

        $csvContent = [];
        $csvContent[] = ['Nom', 'Catalogue Projet', 'Nombre de Contacts', 'Type', 'Prix Unitaire'];

        foreach ($connecteurs as $connecteur) {
            $catalogue = $connecteur->getCatalogueProjetConnecteurs();
            $csvContent[] = [
                $connecteur->getNom(),
                $catalogue ? $catalogue->getNom() : 'N/A',
                $catalogue ? $catalogue->getNombreContacts() : 'N/A',
                $catalogue ? $catalogue->getType() : 'N/A',
                $catalogue ? $catalogue->getPrixUnitaire() : 'N/A',
            ];
        }

        $response = new Response($this->arrayToCsv($csvContent));
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'connecteurs_' . $projetId . '_' . date('Ymd_His') . '.csv'
        );
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    #[Route('/projet/{projetId}/connecteurs/{id}/edit', name: 'connecteur_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $projetId,
        int $id,
        Request $request,
        EntityManagerInterface $em,
        ProjetRepository $projetRepository,
        FormFactoryInterface $formFactory
    ): Response {
        $projet = $projetRepository->find($projetId);
        if (!$projet) {
            throw $this->createNotFoundException('Projet non trouvé');
        }

        $connecteur = $em->getRepository(Connecteur::class)->find($id);
        if (!$connecteur || $connecteur->getProjet()->getId() !== $projetId) {
            throw $this->createNotFoundException('Connecteur non trouvé pour ce projet');
        }

        $this->denyAccessUnlessGranted('CAN_EDIT_CONNECTEURS', $projet);

        $form = $formFactory->create(ConnecteurType::class, $connecteur, ['projet_id' => $projetId]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Connecteur modifié avec succès');
            return $this->redirectToRoute('connecteur_list', ['projetId' => $projetId]);
        }

        return $this->render('connecteur/edit.html.twig', [
            'form' => $form->createView(),
            'projet' => $projet,
        ]);
    }

    #[Route('/projet/{projetId}/connecteurs/{id}', name: 'connecteur_delete', methods: ['POST'])]
    public function delete(
        int $projetId,
        int $id,
        Request $request,
        EntityManagerInterface $em,
        ProjetRepository $projetRepository
    ): Response {
        $projet = $projetRepository->find($projetId);
        if (!$projet) {
            throw $this->createNotFoundException('Projet non trouvé');
        }

        $connecteur = $em->getRepository(Connecteur::class)->find($id);
        if (!$connecteur || $connecteur->getProjet()->getId() !== $projetId) {
            throw $this->createNotFoundException('Connecteur non trouvé pour ce projet');
        }

        $this->denyAccessUnlessGranted('CAN_EDIT_CONNECTEURS', $projet);

        if ($this->isCsrfTokenValid('delete_' . $connecteur->getId(), $request->request->get('_token'))) {
            $em->remove($connecteur);
            $em->flush();
            $this->addFlash('success', 'Connecteur supprimé avec succès');
        } else {
            $this->addFlash('error', 'Token invalide, suppression annulée');
        }

        return $this->redirectToRoute('connecteur_list', ['projetId' => $projetId]);
    }

    private function arrayToCsv(array $data): string
    {
        $output = fopen('php://temp', 'r+');
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        return $csv;
    }
}
