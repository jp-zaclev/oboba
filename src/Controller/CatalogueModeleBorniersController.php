<?php
namespace App\Controller;

use App\Entity\CatalogueModeleBorniers;
use App\Entity\CatalogueBorne; // Ajouté
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
    use FilterHelperTrait;

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

        $qb = $this->repository->createQueryBuilder('b')
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

        return $this->render('catalogue_modele_borniers/list.html.twig', [
            'items' => $items,
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

            // Si aucune borne n’a été ajoutée manuellement, générer des bornes par défaut
            if ($bornier->getCatalogueBornes()->isEmpty() && $bornier->getNombreBornes() > 0) {
                for ($i = 1; $i <= $bornier->getNombreBornes(); $i++) {
                    $borne = new CatalogueBorne();
                    $borne->setAttribut("$i"); // Valeur par défaut, ajustable
                    $bornier->addCatalogueBorne($borne);
                    $entityManager->persist($borne);
                }
            }

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
            $entityManager = $this->getDoctrine()->getManager();

            // Optionnel : appliquer la même logique pour l’édition
            if ($bornier->getCatalogueBornes()->isEmpty() && $bornier->getNombreBornes() > 0) {
                for ($i = 1; $i <= $bornier->getNombreBornes(); $i++) {
                    $borne = new CatalogueBorne();
                    $borne->setAttribut("$i");
                    $bornier->addCatalogueBorne($borne);
                    $entityManager->persist($borne);
                }
            }

            $entityManager->flush();

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
