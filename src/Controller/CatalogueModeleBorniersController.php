<?php
namespace App\Controller;

use App\Entity\CatalogueModeleBorniers;
use App\Form\CatalogueModeleBorniersType;
use App\Form\CatalogueModeleBorniersFilterType;
use App\Repository\CatalogueModeleBorniersRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/catalogue/modele/borniers")
 * @IsGranted("ROLE_ADMIN")
 */
class CatalogueModeleBorniersController extends AbstractController
{
    private $repository;

    public function __construct(CatalogueModeleBorniersRepository $repository)
    {
        $this->repository = $repository;
    }

/**
     * @Route("/", name="catalogue_modele_borniers_list", methods={"GET", "POST"})
     */
    public function list(Request $request, PaginatorInterface $paginator): Response
    {
        $session = $request->getSession();
        $selectedIds = $session->get('selected_modele_borniers', []);

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
                    $this->addFlash('success', 'Modèles de borniers sélectionnés supprimés avec succès.');
                    $session->set('selected_modele_borniers', []);
                }
            }
            return $this->redirectToRoute('catalogue_modele_borniers_list');
        }

        // Mise à jour des sélections via GET (AJAX)
        if ($request->query->has('toggle_selection')) {
            $itemId = $request->query->get('item_id');
            if (in_array($itemId, $selectedIds)) {
                $selectedIds = array_diff($selectedIds, [$itemId]);
            } else {
                $selectedIds[] = $itemId;
            }
            $session->set('selected_modele_borniers', $selectedIds);
            return $this->json(['success' => true, 'selected' => $selectedIds]);
        }

        $filterForm = $this->createForm(CatalogueModeleBorniersFilterType::class);
        $filterForm->handleRequest($request);

        $qb = $this->repository->createQueryBuilder('b');

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();
            if ($data['nom']) {
                $qb->andWhere('b.nom LIKE :nom')->setParameter('nom', '%' . $data['nom'] . '%');
            }
            if ($data['nombreBornesMin']) {
                $qb->andWhere('b.nombreBornes >= :bornesMin')->setParameter('bornesMin', $data['nombreBornesMin']);
            }
            if ($data['nombreBornesMax']) {
                $qb->andWhere('b.nombreBornes <= :bornesMax')->setParameter('bornesMax', $data['nombreBornesMax']);
            }
            if ($data['caracteristiques']) {
                $qb->andWhere('b.caracteristiques LIKE :caracteristiques')->setParameter('caracteristiques', '%' . $data['caracteristiques'] . '%');
            }
            if ($data['prixUnitaireMin']) {
                $qb->andWhere('b.prixUnitaire >= :prixMin')->setParameter('prixMin', $data['prixUnitaireMin']);
            }
            if ($data['prixUnitaireMax']) {
                $qb->andWhere('b.prixUnitaire <= :prixMax')->setParameter('prixMax', $data['prixUnitaireMax']);
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

        return $this->render('catalogue_modele_borniers/list.html.twig', [
            'borniers' => $borniers,
            'filter_form' => $filterForm->createView(),
            'selected_ids' => $selectedIds,
        ]);
    }

    /**
     * @Route("/new", name="catalogue_modele_borniers_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $bornier = new CatalogueModeleBorniers();
        $form = $this->createForm(CatalogueModeleBorniersType::class, $bornier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bornier);
            $entityManager->flush();

            $this->addFlash('success', 'Modèle de bornier ajouté avec succès.');
            return $this->redirectToRoute('catalogue_modele_borniers_list');
        }

        return $this->render('catalogue_modele_borniers/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="catalogue_modele_borniers_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CatalogueModeleBorniers $bornier): Response
    {
        $form = $this->createForm(CatalogueModeleBorniersType::class, $bornier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Modèle de bornier modifié avec succès.');
            return $this->redirectToRoute('catalogue_modele_borniers_list');
        }

        return $this->render('catalogue_modele_borniers/edit.html.twig', [
            'bornier' => $bornier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="catalogue_modele_borniers_delete", methods={"POST"})
     */
    public function delete(Request $request, CatalogueModeleBorniers $bornier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bornier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bornier);
            $entityManager->flush();

            $this->addFlash('success', 'Modèle de bornier supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('catalogue_modele_borniers_list');
    }
}
