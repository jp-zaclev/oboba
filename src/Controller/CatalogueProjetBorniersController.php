<?php
namespace App\Controller;

use App\Entity\CatalogueProjetBorniers;
use App\Entity\CatalogueModeleBorniers;
use App\Entity\Projet;
use App\Form\CatalogueProjetBorniersFilterType;
use App\Form\CatalogueProjetBorniersType;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Knp\Component\Pager\PaginatorInterface;

class CatalogueProjetBorniersController extends BaseController
{
    #[Route('/projet/{projetId}/catalogue-borniers', name: 'catalogue_projet_borniers_list', methods: ['GET', 'POST'])]
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

        $this->checkProjectAccess($projet, $em);

        $session = $request->getSession();
        $selectedIds = $session->get('selected_borniers_' . $projetId, []);

        // Gestion des suppressions multiples via POST
        if ($request->isMethod('POST') && $this->isGranted('CAN_EDIT_BORNIERS', $projet)) {
            if (empty($selectedIds)) {
                $this->addFlash('warning', 'Aucun élément sélectionné pour la suppression.');
            } else {
                $itemsToDelete = $em->getRepository(CatalogueProjetBorniers::class)->findBy(['id' => $selectedIds, 'projet' => $projet]);
                if (empty($itemsToDelete)) {
                    $this->addFlash('error', 'Aucun élément valide trouvé pour la suppression.');
                } else {
                    foreach ($itemsToDelete as $item) {
                        $em->remove($item);
                    }
                    $em->flush();
                    $this->addFlash('success', 'Borniers sélectionnés supprimés avec succès.');
                    $session->set('selected_borniers_' . $projetId, []);
                }
            }
            return $this->redirectToRoute('catalogue_projet_borniers_list', ['projetId' => $projetId]);
        }

        // Mise à jour des sélections via GET (AJAX)
        if ($request->query->has('toggle_selection') && $this->isGranted('CAN_EDIT_BORNIERS', $projet)) {
            $itemId = $request->query->get('item_id');
            if (in_array($itemId, $selectedIds)) {
                $selectedIds = array_diff($selectedIds, [$itemId]);
            } else {
                $selectedIds[] = $itemId;
            }
            $session->set('selected_borniers_' . $projetId, $selectedIds);
            return $this->json(['success' => true, 'selected' => $selectedIds]);
        }

        $filterForm = $formFactory->create(CatalogueProjetBorniersFilterType::class);
        $filterForm->handleRequest($request);

        $qb = $em->getRepository(CatalogueProjetBorniers::class)->createQueryBuilder('b')
            ->where('b.projet = :projet')
            ->setParameter('projet', $projet);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();

            if ($data['nom']) {
                $qb->andWhere('b.nom LIKE :nom')
                   ->setParameter('nom', '%' . $data['nom'] . '%');
            }
            if ($data['nombreBornes'] !== null) {
                $qb->andWhere('b.nombreBornes = :nombreBornes')
                   ->setParameter('nombreBornes', $data['nombreBornes']);
            }
            if ($data['caracteristiques']) {
                $qb->andWhere('b.caracteristiques LIKE :caracteristiques')
                   ->setParameter('caracteristiques', '%' . $data['caracteristiques'] . '%');
            }
            if ($data['prixUnitaireMin'] !== null) {
                $qb->andWhere('b.prixUnitaire >= :prixUnitaireMin')
                   ->setParameter('prixUnitaireMin', $data['prixUnitaireMin']);
            }
            if ($data['prixUnitaireMax'] !== null) {
                $qb->andWhere('b.prixUnitaire <= :prixUnitaireMax')
                   ->setParameter('prixUnitaireMax', $data['prixUnitaireMax']);
            }
        }

        $borniers = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10,
            [
                'defaultSortFieldName' => 'b.nom',
                'defaultSortDirection' => 'asc',
            ]
        );

        return $this->render('catalogue_projet_borniers/list.html.twig', [
            'projet' => $projet,
            'borniers' => $borniers,
            'filter_form' => $filterForm->createView(),
            'selected_ids' => $selectedIds,
        ]);
    }

    #[Route('/projet/{projetId}/catalogue-borniers/new', name: 'catalogue_projet_borniers_new', methods: ['GET', 'POST'])]
    public function new(
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

        $this->denyAccessUnlessGranted('CAN_EDIT_BORNIERS', $projet);

        $bornier = new CatalogueProjetBorniers();
        $bornier->setProjet($projet);
        $form = $formFactory->create(CatalogueProjetBorniersType::class, $bornier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($bornier);
            $em->flush();
            $this->addFlash('success', 'Bornier ajouté au catalogue avec succès');
            return $this->redirectToRoute('catalogue_projet_borniers_list', ['projetId' => $projet->getId()]);
        }

        return $this->render('catalogue_projet_borniers/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/catalogue-borniers/{id}/edit', name: 'catalogue_projet_borniers_edit', methods: ['GET', 'POST'])]
    public function edit(
        CatalogueProjetBorniers $bornier,
        Request $request,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory
    ): Response {
        $projet = $bornier->getProjet();
        $this->denyAccessUnlessGranted('CAN_EDIT_BORNIERS', $projet);

        $form = $formFactory->create(CatalogueProjetBorniersType::class, $bornier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Bornier du catalogue modifié avec succès');
            return $this->redirectToRoute('catalogue_projet_borniers_list', ['projetId' => $projet->getId()]);
        }

        return $this->render('catalogue_projet_borniers/edit.html.twig', [
            'projet' => $projet,
            'bornier' => $bornier,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/catalogue-borniers/{id}/delete', name: 'catalogue_projet_borniers_delete', methods: ['POST'])]
    public function delete(
        CatalogueProjetBorniers $bornier,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $projet = $bornier->getProjet();
        $this->denyAccessUnlessGranted('CAN_EDIT_BORNIERS', $projet);

        if (!$this->isCsrfTokenValid('delete_' . $bornier->getId(), $request->request->get('_token'))) {
            throw new AccessDeniedException('Token CSRF invalide');
        }

        $em->remove($bornier);
        $em->flush();
        $this->addFlash('success', 'Bornier supprimé du catalogue avec succès');
        return $this->redirectToRoute('catalogue_projet_borniers_list', ['projetId' => $projet->getId()]);
    }

#[Route('/catalogue/projet/{projetId}/borniers/import', name: 'catalogue_projet_borniers_import', methods: ['POST'])]
    public function importFromModele(int $projetId, Request $request, EntityManagerInterface $em): Response
    {
        $utilisateur = $this->getUser();
        if (!$utilisateur) {
            return $this->redirectToRoute('login');
        }

        $projet = $em->getRepository(Projet::class)->find($projetId);
        if (!$projet) {
            throw $this->createNotFoundException('Projet non trouvé.');
        }

        if (!$this->isGranted('CAN_EDIT_BORNIERS', $projet)) {
            throw $this->createAccessDeniedException('Vous n’avez pas le droit d’importer des borniers pour ce projet.');
        }

        if ($this->isCsrfTokenValid('import_borniers_' . $projet->getId(), $request->request->get('_token'))) {
            $importedCount = 0;
            $existingBorniers = $projet->getCatalogueProjetBorniers()->map(fn($bornier) => $bornier->getNom())->toArray();
            $modeleBorniers = $em->getRepository(CatalogueModeleBorniers::class)->findAll();

            foreach ($modeleBorniers as $modele) {
                if (!in_array($modele->getNom(), $existingBorniers)) {
                    $projetBornier = new CatalogueProjetBorniers();
                    $projetBornier->setProjet($projet)
                                  ->setNom($modele->getNom())
                                  ->setNombreBornes($modele->getNombreBornes())
                                  ->setCaracteristiques($modele->getCaracteristiques())
                                  ->setPrixUnitaire($modele->getPrixUnitaire());
                    $em->persist($projetBornier);
                    $projet->addCatalogueProjetBornier($projetBornier);
                    $importedCount++;
                }
            }

            $projet->setDateHeureDerniereModification(new \DateTime());
            $em->flush();

            if ($importedCount > 0) {
                $this->addFlash('success', "$importedCount nouveaux borniers ont été importés depuis le catalogue modèle.");
            } else {
                $this->addFlash('info', 'Aucun nouveau bornier à importer depuis le catalogue modèle.');
            }
        } else {
            $this->addFlash('danger', 'Erreur de sécurité lors de l’importation des borniers.');
        }

        return $this->redirectToRoute('catalogue_projet_borniers_list', ['projetId' => $projet->getId()]);
    }

}
