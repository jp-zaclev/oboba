<?php
namespace App\Controller;

use App\Entity\CatalogueProjetBorniers;
use App\Entity\CatalogueModeleBorniers;
use App\Entity\Projet;
use App\Entity\CatalogueBorne;
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
    use FilterHelperTrait;

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
            ->setParameter('projet', $projet)
            ->leftJoin('b.catalogueBornes', 'cb')
            ->addSelect('cb');

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();
            if ($data['nom']) {
                $qb->andWhere('b.nom LIKE :nom')->setParameter('nom', '%' . $data['nom'] . '%');
            }
            if ($data['nombreBornes'] !== null && $data['nombreBornes'] !== '') {
                $this->applyNumericFilter($qb, 'b.nombreBornes', $data['nombreBornes'], 'bornes');
            }
            if ($data['caracteristiques']) {
                $qb->andWhere('b.caracteristiques LIKE :caracteristiques')->setParameter('caracteristiques', '%' . $data['caracteristiques'] . '%');
            }
            if ($data['prixUnitaire'] !== null && $data['prixUnitaire'] !== '') {
                $this->applyNumericFilter($qb, 'b.prixUnitaire', $data['prixUnitaire'], 'prix');
            }
        }

        $items = $paginator->paginate(
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
            'items' => $items,
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
            // Si aucune borne n’a été ajoutée manuellement, générer des bornes par défaut
            if ($bornier->getCatalogueBornes()->isEmpty() && $bornier->getNombreBornes() > 0) {
                for ($i = 1; $i <= $bornier->getNombreBornes(); $i++) {
                    $borne = new CatalogueBorne();
                    $borne->setAttribut("$i");
                    $bornier->addCatalogueBorne($borne);
                    $em->persist($borne);
                }
            }

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

    #[Route('/projet/{projetId}/catalogue-borniers/{id}/edit', name: 'catalogue_projet_borniers_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $projetId,
        CatalogueProjetBorniers $bornier,
        Request $request,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory
    ): Response {
        $projet = $bornier->getProjet();
        if ($projet->getId() !== $projetId) {
            throw $this->createNotFoundException('Bornier ou projet non trouvé');
        }
        $this->denyAccessUnlessGranted('CAN_EDIT_BORNIERS', $projet);

        $form = $formFactory->create(CatalogueProjetBorniersType::class, $bornier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Optionnel : générer des bornes si la collection est vide
            if ($bornier->getCatalogueBornes()->isEmpty() && $bornier->getNombreBornes() > 0) {
                for ($i = 1; $i <= $bornier->getNombreBornes(); $i++) {
                    $borne = new CatalogueBorne();
                    $borne->setAttribut("$i");
                    $bornier->addCatalogueBorne($borne);
                    $em->persist($borne);
                }
            }

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

    #[Route('/projet/{projetId}/catalogue-borniers/{id}/delete', name: 'catalogue_projet_borniers_delete', methods: ['POST'])]
    public function delete(
        int $projetId,
        CatalogueProjetBorniers $bornier,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $projet = $bornier->getProjet();
        if ($projet->getId() !== $projetId) {
            throw $this->createNotFoundException('Bornier ou projet non trouvé');
        }
        $this->denyAccessUnlessGranted('CAN_EDIT_BORNIERS', $projet);

        if (!$this->isCsrfTokenValid('delete_' . $bornier->getId(), $request->request->get('_token'))) {
            throw new AccessDeniedException('Token CSRF invalide');
        }

        $em->remove($bornier);
        $em->flush();
        $this->addFlash('success', 'Bornier supprimé du catalogue avec succès');
        return $this->redirectToRoute('catalogue_projet_borniers_list', ['projetId' => $projet->getId()]);
    }

    #[Route('/projet/{projetId}/catalogue-borniers/import', name: 'catalogue_projet_borniers_import', methods: ['POST'])]
    public function importFromModele(
        int $projetId,
        Request $request,
        EntityManagerInterface $em
    ): Response {
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

                    foreach ($modele->getCatalogueBornes() as $borneModele) {
                        $newBorne = new CatalogueBorne();
                        $newBorne->setAttribut($borneModele->getAttribut());
                        $projetBornier->addCatalogueBorne($newBorne);
                        $em->persist($newBorne);
                    }

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
