<?php
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
    use FilterHelperTrait;

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

        $qb = $this->repository->createQueryBuilder('c')
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

        return $this->render('catalogue_modele_cables/list.html.twig', [
	    'items' => $catalogues,
	    'filter_form' => $filterForm->createView(),
	    'selected_ids' => $selectedIds,
	]);
    }

    /**
     * @Route("/new", name="catalogue_modele_cables_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $catalogueModeleCable = new CatalogueModeleCables();
        $form = $this->createForm(CatalogueModeleCablesType::class, $catalogueModeleCable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($catalogueModeleCable);
            $entityManager->flush();

            $this->addFlash('success', 'Modèle de câble créé avec succès.');
       	    return $this->redirectToRoute('catalogue_modele_cables_edit', ['id' => $catalogueModeleCable->getId()]);
        }

        return $this->render('catalogue_modele_cables/new.html.twig', [
            'catalogue_modele_cable' => $catalogueModeleCable,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="catalogue_modele_cables_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CatalogueModeleCables $catalogueModeleCables): Response
    {
        $form = $this->createForm(CatalogueModeleCablesType::class, $catalogueModeleCables);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Modèle de câble mis à jour avec succès.');
            return $this->redirectToRoute('catalogue_modele_cables_list');
        }

        return $this->render('catalogue_modele_cables/edit.html.twig', [
            'catalogue' => $catalogueModeleCables,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="catalogue_modele_cables_delete", methods={"POST"})
     */
    public function delete(Request $request, CatalogueModeleCables $catalogueModeleCables): Response
    {
        if ($this->isCsrfTokenValid('delete'.$catalogueModeleCables->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($catalogueModeleCables);
            $entityManager->flush();

            $this->addFlash('success', 'Modèle de câble supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('catalogue_modele_cables_list');
    }
}
