<?php
// src/Controller/CatalogueModeleCablesController.php
namespace App\Controller;

use App\Entity\CatalogueModeleCables;
use App\Form\CatalogueModeleCablesType;
use App\Form\CatalogueModeleCablesFilterType;
use App\Repository\CatalogueModeleCablesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/catalogue/modele/cables")
 * @IsGranted("ROLE_ADMIN")
 */
class CatalogueModeleCablesController extends AbstractController
{
    private $repository;

    public function __construct(CatalogueModeleCablesRepository $repository)
    {
        $this->repository = $repository;
    }

/**
     * @Route("/", name="catalogue_modele_cables_list", methods={"GET", "POST"})
     */
    public function list(Request $request, PaginatorInterface $paginator): Response
    {
        $session = $request->getSession();
        $selectedIds = $session->get('selected_modele_cables', []);

        // Gestion des suppressions multiples via POST
        if ($request->isMethod('POST')) {
            if (empty($selectedIds)) {
                $this->addFlash('warning', 'Aucun élément sélectionné pour la suppression.');
            } else {
                $entityManager = $this->getDoctrine()->getManager();
                $itemsToDelete = $this->repository->findBy(['id' => $selectedIds]);
                if (empty($itemsToDelete)) {
                    $this->addFlash('error', 'Aucun élément valide trouvé pour la suppression.');
                } else {
                    foreach ($itemsToDelete as $item) {
                        $entityManager->remove($item);
                    }
                    $entityManager->flush();
                    $this->addFlash('success', 'Modèles de câbles sélectionnés supprimés avec succès.');
                    $session->set('selected_modele_cables', []);
                }
            }
            return $this->redirectToRoute('catalogue_modele_cables_list');
        }

        // Mise à jour des sélections via GET (AJAX)
        if ($request->query->has('toggle_selection')) {
            $itemId = $request->query->get('item_id');
            if (in_array($itemId, $selectedIds)) {
                $selectedIds = array_diff($selectedIds, [$itemId]);
            } else {
                $selectedIds[] = $itemId;
            }
            $session->set('selected_modele_cables', $selectedIds);
            return $this->json(['success' => true, 'selected' => $selectedIds]);
        }

        $filterForm = $this->createForm(CatalogueModeleCablesFilterType::class);
        $filterForm->handleRequest($request);

        $qb = $this->repository->createQueryBuilder('c');

        // Gestion des filtres
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();
            if ($data['nom']) {
                $qb->andWhere('c.nom LIKE :nom')->setParameter('nom', '%' . $data['nom'] . '%');
            }
            if ($data['type']) {
                $qb->andWhere('c.type LIKE :type')->setParameter('type', '%' . $data['type'] . '%');
            }
            if ($data['nombreConducteursMaxMin']) {
                $qb->andWhere('c.nombreConducteursMax >= :conducteursMin')->setParameter('conducteursMin', $data['nombreConducteursMaxMin']);
            }
            if ($data['nombreConducteursMaxMax']) {
                $qb->andWhere('c.nombreConducteursMax <= :conducteursMax')->setParameter('conducteursMax', $data['nombreConducteursMaxMax']);
            }
            if ($data['prixUnitaireMin']) {
                $qb->andWhere('c.prixUnitaire >= :prixMin')->setParameter('prixMin', $data['prixUnitaireMin']);
            }
            if ($data['prixUnitaireMax']) {
                $qb->andWhere('c.prixUnitaire <= :prixMax')->setParameter('prixMax', $data['prixUnitaireMax']);
            }
        }

        // Pagination sans tri manuel
        $catalogues = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10,
            [
                'defaultSortFieldName' => 'c.nom',
                'defaultSortDirection' => 'asc',
            ]
        );

        return $this->render('catalogue_modele_cables/list.html.twig', [
            'catalogues' => $catalogues,
            'filter_form' => $filterForm->createView(),
            'selected_ids' => $selectedIds,
        ]);
    }


    /**
     * Ajoute un nouveau modèle de câble
     * @Route("/new", name="catalogue_modele_cables_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $catalogue = new CatalogueModeleCables();
        $form = $this->createForm(CatalogueModeleCablesType::class, $catalogue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($catalogue);
            $entityManager->flush();

            $this->addFlash('success', 'Modèle de câble ajouté avec succès.');
            return $this->redirectToRoute('catalogue_modele_cables_list');
        }

        return $this->render('catalogue_modele_cables/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Modifie un modèle de câble existant
     * @Route("/{id}/edit", name="catalogue_modele_cables_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CatalogueModeleCables $catalogue): Response
    {
        $form = $this->createForm(CatalogueModeleCablesType::class, $catalogue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Modèle de câble modifié avec succès.');
            return $this->redirectToRoute('catalogue_modele_cables_list');
        }

        return $this->render('catalogue_modele_cables/edit.html.twig', [
            'catalogue' => $catalogue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Supprime un modèle de câble
     * @Route("/{id}", name="catalogue_modele_cables_delete", methods={"POST"})
     */
    public function delete(Request $request, CatalogueModeleCables $catalogue): Response
    {
        if ($this->isCsrfTokenValid('delete'.$catalogue->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($catalogue);
            $entityManager->flush();

            $this->addFlash('success', 'Modèle de câble supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('catalogue_modele_cables_list');
    }
}
