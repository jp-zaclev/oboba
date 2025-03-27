<?php
namespace App\Controller;

use App\Entity\CatalogueProjetConnecteurs;
use App\Entity\CatalogueModeleConnecteurs;
use App\Entity\Projet;
use App\Form\CatalogueProjetConnecteursFilterType;
use App\Form\CatalogueProjetConnecteursType;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Knp\Component\Pager\PaginatorInterface;

class CatalogueProjetConnecteursController extends BaseController
{
    #[Route('/projet/{projetId}/catalogue-connecteurs', name: 'catalogue_projet_connecteurs_list', methods: ['GET', 'POST'])]
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
        $selectedIds = $session->get('selected_connecteurs_' . $projetId, []);

        // Gestion des suppressions multiples via POST
        if ($request->isMethod('POST') && $this->isGranted('CAN_EDIT_CONNECTEURS', $projet)) {
            if (empty($selectedIds)) {
                $this->addFlash('warning', 'Aucun élément sélectionné pour la suppression.');
            } else {
                $itemsToDelete = $em->getRepository(CatalogueProjetConnecteurs::class)->findBy(['id' => $selectedIds, 'projet' => $projet]);
                if (empty($itemsToDelete)) {
                    $this->addFlash('error', 'Aucun élément valide trouvé pour la suppression.');
                } else {
                    foreach ($itemsToDelete as $item) {
                        $em->remove($item);
                    }
                    $em->flush();
                    $this->addFlash('success', 'Connecteurs sélectionnés supprimés avec succès.');
                    $session->set('selected_connecteurs_' . $projetId, []);
                }
            }
            return $this->redirectToRoute('catalogue_projet_connecteurs_list', ['projetId' => $projetId]);
        }

        // Mise à jour des sélections via GET (AJAX)
        if ($request->query->has('toggle_selection') && $this->isGranted('CAN_EDIT_CONNECTEURS', $projet)) {
            $itemId = $request->query->get('item_id');
            if (in_array($itemId, $selectedIds)) {
                $selectedIds = array_diff($selectedIds, [$itemId]);
            } else {
                $selectedIds[] = $itemId;
            }
            $session->set('selected_connecteurs_' . $projetId, $selectedIds);
            return $this->json(['success' => true, 'selected' => $selectedIds]);
        }

        $filterForm = $formFactory->create(CatalogueProjetConnecteursFilterType::class);
        $filterForm->handleRequest($request);

        $qb = $em->getRepository(CatalogueProjetConnecteurs::class)->createQueryBuilder('c')
            ->where('c.projet = :projet')
            ->setParameter('projet', $projet);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();

            if ($data['nom']) {
                $qb->andWhere('c.nom LIKE :nom')
                   ->setParameter('nom', '%' . $data['nom'] . '%');
            }
            if ($data['type']) {
                $qb->andWhere('c.type LIKE :type')
                   ->setParameter('type', '%' . $data['type'] . '%');
            }
            if ($data['nombreContacts'] !== null) {
                $qb->andWhere('c.nombreContacts = :nombreContacts')
                   ->setParameter('nombreContacts', $data['nombreContacts']);
            }
            if ($data['prixUnitaireMin'] !== null) {
                $qb->andWhere('c.prixUnitaire >= :prixUnitaireMin')
                   ->setParameter('prixUnitaireMin', $data['prixUnitaireMin']);
            }
            if ($data['prixUnitaireMax'] !== null) {
                $qb->andWhere('c.prixUnitaire <= :prixUnitaireMax')
                   ->setParameter('prixUnitaireMax', $data['prixUnitaireMax']);
            }
        }

        $connecteurs = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10,
            [
                'defaultSortFieldName' => 'c.nom',
                'defaultSortDirection' => 'asc',
            ]
        );

        return $this->render('catalogue_projet_connecteurs/list.html.twig', [
            'projet' => $projet,
            'connecteurs' => $connecteurs,
            'filter_form' => $filterForm->createView(),
            'selected_ids' => $selectedIds,
        ]);
    }

    #[Route('/projet/{projetId}/catalogue-connecteurs/new', name: 'catalogue_projet_connecteurs_new', methods: ['GET', 'POST'])]
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

        $this->denyAccessUnlessGranted('CAN_EDIT_CONNECTEURS', $projet);

        $connecteur = new CatalogueProjetConnecteurs();
        $connecteur->setProjet($projet);
        $form = $formFactory->create(CatalogueProjetConnecteursType::class, $connecteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($connecteur);
            $em->flush();
            $this->addFlash('success', 'Connecteur ajouté au catalogue avec succès');
            return $this->redirectToRoute('catalogue_projet_connecteurs_list', ['projetId' => $projet->getId()]);
        }

        return $this->render('catalogue_projet_connecteurs/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/catalogue-connecteurs/{id}/edit', name: 'catalogue_projet_connecteurs_edit', methods: ['GET', 'POST'])]
    public function edit(
        CatalogueProjetConnecteurs $connecteur,
        Request $request,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory
    ): Response {
        $projet = $connecteur->getProjet();
        $this->denyAccessUnlessGranted('CAN_EDIT_CONNECTEURS', $projet);

        $form = $formFactory->create(CatalogueProjetConnecteursType::class, $connecteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Connecteur du catalogue modifié avec succès');
            return $this->redirectToRoute('catalogue_projet_connecteurs_list', ['projetId' => $projet->getId()]);
        }

        return $this->render('catalogue_projet_connecteurs/edit.html.twig', [
            'projet' => $projet,
            'connecteur' => $connecteur,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/catalogue-connecteurs/{id}/delete', name: 'catalogue_projet_connecteurs_delete', methods: ['POST'])]
    public function delete(
        CatalogueProjetConnecteurs $connecteur,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $projet = $connecteur->getProjet();
        $this->denyAccessUnlessGranted('CAN_EDIT_CONNECTEURS', $projet);

        if (!$this->isCsrfTokenValid('delete_' . $connecteur->getId(), $request->request->get('_token'))) {
            throw new AccessDeniedException('Token CSRF invalide');
        }

        $em->remove($connecteur);
        $em->flush();
        $this->addFlash('success', 'Connecteur supprimé du catalogue avec succès');
        return $this->redirectToRoute('catalogue_projet_connecteurs_list', ['projetId' => $projet->getId()]);
    }

#[Route('/catalogue/projet/{projetId}/connecteurs/import', name: 'catalogue_projet_connecteurs_import', methods: ['POST'])]
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

        if (!$this->isGranted('CAN_EDIT_CONNECTEURS', $projet)) {
            throw $this->createAccessDeniedException('Vous n’avez pas le droit d’importer des connecteurs pour ce projet.');
        }

        if ($this->isCsrfTokenValid('import_connecteurs_' . $projet->getId(), $request->request->get('_token'))) {
            $importedCount = 0;
            $existingConnecteurs = $projet->getCatalogueProjetConnecteurs()->map(fn($connecteur) => $connecteur->getNom())->toArray();
            $modeleConnecteurs = $em->getRepository(CatalogueModeleConnecteurs::class)->findAll();

            foreach ($modeleConnecteurs as $modele) {
                if (!in_array($modele->getNom(), $existingConnecteurs)) {
                    $projetConnecteur = new CatalogueProjetConnecteurs();
                    $projetConnecteur->setProjet($projet)
                                     ->setNom($modele->getNom())
                                     ->setType($modele->getType())
                                     ->setNombreContacts($modele->getNombreContacts())
                                     ->setPrixUnitaire($modele->getPrixUnitaire());
                    $em->persist($projetConnecteur);
                    $projet->addCatalogueProjetConnecteur($projetConnecteur);
                    $importedCount++;
                }
            }

            $projet->setDateHeureDerniereModification(new \DateTime());
            $em->flush();

            if ($importedCount > 0) {
                $this->addFlash('success', "$importedCount nouveaux connecteurs ont été importés depuis le catalogue modèle.");
            } else {
                $this->addFlash('info', 'Aucun nouveau connecteur à importer depuis le catalogue modèle.');
            }
        } else {
            $this->addFlash('danger', 'Erreur de sécurité lors de l’importation des connecteurs.');
        }

        return $this->redirectToRoute('catalogue_projet_connecteurs_list', ['projetId' => $projet->getId()]);
    }

}
