<?php
namespace App\Controller;

use App\Entity\CatalogueProjetCables;
use App\Entity\Projet;
use App\Entity\CatalogueModeleCables;
use App\Entity\CatalogueConducteur;
use App\Form\CatalogueProjetCablesFilterType;
use App\Form\CatalogueProjetCablesType;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Knp\Component\Pager\PaginatorInterface;

class CatalogueProjetCablesController extends BaseController
{
    use FilterHelperTrait;

    #[Route('/projet/{projetId}/catalogue-cables', name: 'catalogue_projet_cables_list', methods: ['GET', 'POST'])]
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
        $selectedIds = $session->get('selected_cables_' . $projetId, []);

        if ($request->isMethod('POST') && $this->isGranted('CAN_EDIT_CABLES', $projet)) {
            if (empty($selectedIds)) {
                $this->addFlash('warning', 'Aucun élément sélectionné pour la suppression.');
            } else {
                $itemsToDelete = $em->getRepository(CatalogueProjetCables::class)->findBy(['id' => $selectedIds, 'projet' => $projet]);
                if (empty($itemsToDelete)) {
                    $this->addFlash('error', 'Aucun élément valide trouvé pour la suppression.');
                } else {
                    foreach ($itemsToDelete as $item) {
                        $em->remove($item);
                    }
                    $em->flush();
                    $this->addFlash('success', 'Câbles sélectionnés supprimés avec succès.');
                    $session->set('selected_cables_' . $projetId, []);
                }
            }
            return $this->redirectToRoute('catalogue_projet_cables_list', ['projetId' => $projetId]);
        }

        if ($request->query->has('toggle_selection') && $this->isGranted('CAN_EDIT_CABLES', $projet)) {
            $itemId = $request->query->get('item_id');
            if (in_array($itemId, $selectedIds)) {
                $selectedIds = array_diff($selectedIds, [$itemId]);
            } else {
                $selectedIds[] = $itemId;
            }
            $session->set('selected_cables_' . $projetId, $selectedIds);
            return $this->json(['success' => true, 'selected' => $selectedIds]);
        }

        $filterForm = $formFactory->create(CatalogueProjetCablesFilterType::class);
        $filterForm->handleRequest($request);

        $qb = $em->getRepository(CatalogueProjetCables::class)->createQueryBuilder('c')
            ->where('c.projet = :projet')
            ->setParameter('projet', $projet)
            ->leftJoin('c.catalogueConducteurs', 'cc')
            ->addSelect('cc');

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();
            if ($data['nom']) {
                $qb->andWhere('c.nom LIKE :nom')->setParameter('nom', '%' . $data['nom'] . '%');
            }
            if ($data['type']) {
                $qb->andWhere('c.type LIKE :type')->setParameter('type', '%' . $data['type'] . '%');
            }
            if ($data['nbConducteurs'] !== null && $data['nbConducteurs'] !== '') {
                $this->applyNumericFilter($qb, 'c.nbConducteurs', $data['nbConducteurs'], 'conducteurs');
            }
            if ($data['prixUnitaire'] !== null && $data['prixUnitaire'] !== '') {
                $this->applyNumericFilter($qb, 'c.prixUnitaire', $data['prixUnitaire'], 'prix');
            }
        }

        $catalogues = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10,
            [
                'defaultSortFieldName' => 'c.nom',
                'defaultSortDirection' => 'asc',
            ]
        );
        
        return $this->render('catalogue_projet_cables/list.html.twig', [
            'projet' => $projet,
            'items' => $catalogues,
            'filter_form' => $filterForm->createView(),
            'selected_ids' => $selectedIds,
        ]);
    }

    #[Route('/projet/{projetId}/catalogue-cables/new', name: 'catalogue_projet_cables_new', methods: ['GET', 'POST'])]
    public function new(int $projetId, Request $request, ProjetRepository $projetRepository, EntityManagerInterface $em): Response
    {
        $projet = $projetRepository->find($projetId);
        if (!$projet) {
            throw $this->createNotFoundException('Projet non trouvé');
        }

        $this->denyAccessUnlessGranted('CAN_EDIT_CABLES', $projet);

        $catalogueProjetCable = new CatalogueProjetCables();
        $catalogueProjetCable->setProjet($projet);
        $form = $this->createForm(CatalogueProjetCablesType::class, $catalogueProjetCable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($catalogueProjetCable);
            $em->flush();
            $this->addFlash('success', 'Type de câble ajouté avec succès.');

            return $this->redirectToRoute('catalogue_projet_cables_list', ['projetId' => $projetId]);
        }

        return $this->render('catalogue_projet_cables/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/projet/{projetId}/catalogue-cables/{id}/edit', name: 'catalogue_projet_cables_edit', methods: ['GET', 'POST'])]
    public function edit(int $projetId, CatalogueProjetCables $catalogueProjetCable, Request $request, ProjetRepository $projetRepository, EntityManagerInterface $em): Response
    {
        $projet = $projetRepository->find($projetId);
        if (!$projet || $catalogueProjetCable->getProjet()->getId() !== $projetId) {
            throw $this->createNotFoundException('Projet ou câble non trouvé');
        }

        $this->denyAccessUnlessGranted('CAN_EDIT_CABLES', $projet);

        $form = $this->createForm(CatalogueProjetCablesType::class, $catalogueProjetCable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Type de câble mis à jour avec succès.');

            return $this->redirectToRoute('catalogue_projet_cables_list', ['projetId' => $projetId]);
        }

        return $this->render('catalogue_projet_cables/edit.html.twig', [
            'projet' => $projet,
            'catalogue_projet_cable' => $catalogueProjetCable,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/projet/{projetId}/catalogue-cables/{id}', name: 'catalogue_projet_cables_delete', methods: ['POST'])]
    public function delete(int $projetId, CatalogueProjetCables $catalogueProjetCable, Request $request, ProjetRepository $projetRepository, EntityManagerInterface $em): Response
    {
        $projet = $projetRepository->find($projetId);
        if (!$projet || $catalogueProjetCable->getProjet()->getId() !== $projetId) {
            throw $this->createNotFoundException('Projet ou câble non trouvé');
        }

        $this->denyAccessUnlessGranted('CAN_EDIT_CABLES', $projet);

        if ($this->isCsrfTokenValid('delete_' . $catalogueProjetCable->getId(), $request->request->get('_token'))) {
            $em->remove($catalogueProjetCable);
            $em->flush();
            $this->addFlash('success', 'Type de câble supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('catalogue_projet_cables_list', ['projetId' => $projetId]);
    }

    #[Route('/projet/{projetId}/catalogue-cables/import', name: 'catalogue_projet_cables_import', methods: ['POST'])]
    public function import(int $projetId, Request $request, ProjetRepository $projetRepository, EntityManagerInterface $em): Response
    {
        $projet = $projetRepository->find($projetId);
        if (!$projet) {
            throw $this->createNotFoundException('Projet non trouvé');
        }

        $this->denyAccessUnlessGranted('CAN_EDIT_CABLES', $projet);

        if ($this->isCsrfTokenValid('import_cables_' . $projet->getId(), $request->request->get('_token'))) {
            $existingCables = $em->getRepository(CatalogueProjetCables::class)->findBy(['projet' => $projet]);
            $existingCableNames = array_map(fn($cable) => $cable->getNom(), $existingCables);

            $modeleCables = $em->getRepository(CatalogueModeleCables::class)->findAll();

            foreach ($modeleCables as $modeleCable) {
                if (!in_array($modeleCable->getNom(), $existingCableNames)) {
                    $newCable = new CatalogueProjetCables();
                    $newCable->setProjet($projet);
                    $newCable->setNom($modeleCable->getNom());
                    $newCable->setType($modeleCable->getType());
                    $newCable->setNbConducteurs($modeleCable->getNbConducteurs());
                    $newCable->setPrixUnitaire($modeleCable->getPrixUnitaire());

                    foreach ($modeleCable->getCatalogueConducteurs() as $conducteur) {
                        $newConducteur = new CatalogueConducteur();
                        $newConducteur->setAttribut($conducteur->getAttribut());
                        $newCable->addCatalogueConducteur($newConducteur);
                        $em->persist($newConducteur);
                    }

                    $em->persist($newCable);
                }
            }

            $em->flush();
            $this->addFlash('success', 'Nouveaux câbles importés avec succès depuis le catalogue modèle.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('catalogue_projet_cables_list', ['projetId' => $projetId]);
    }
}
