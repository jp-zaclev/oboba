<?php
// src/Controller/CatalogueModeleConnecteursController.php
namespace App\Controller;

use App\Entity\CatalogueModeleConnecteurs;
use App\Form\CatalogueModeleConnecteursType;
use App\Form\CatalogueModeleConnecteursFilterType;
use App\Repository\CatalogueModeleConnecteursRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/catalogue/modele/connecteurs")
 * @IsGranted("ROLE_ADMIN")
 */
class CatalogueModeleConnecteursController extends AbstractController
{
    private $repository;

    public function __construct(CatalogueModeleConnecteursRepository $repository)
    {
        $this->repository = $repository;
    }

   /**
     * @Route("/", name="catalogue_modele_connecteurs_list", methods={"GET", "POST"})
     */
    public function list(Request $request, PaginatorInterface $paginator): Response
    {
        $session = $request->getSession();
        $selectedIds = $session->get('selected_modele_connecteurs', []);

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
                    $this->addFlash('success', 'Modèles de connecteurs sélectionnés supprimés avec succès.');
                    $session->set('selected_modele_connecteurs', []);
                }
            }
            return $this->redirectToRoute('catalogue_modele_connecteurs_list');
        }

        // Mise à jour des sélections via GET (AJAX)
        if ($request->query->has('toggle_selection')) {
            $itemId = $request->query->get('item_id');
            if (in_array($itemId, $selectedIds)) {
                $selectedIds = array_diff($selectedIds, [$itemId]);
            } else {
                $selectedIds[] = $itemId;
            }
            $session->set('selected_modele_connecteurs', $selectedIds);
            return $this->json(['success' => true, 'selected' => $selectedIds]);
        }

        $filterForm = $this->createForm(CatalogueModeleConnecteursFilterType::class);
        $filterForm->handleRequest($request);

        $qb = $this->repository->createQueryBuilder('c');

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();
            if ($data['nom']) {
                $qb->andWhere('c.nom LIKE :nom')->setParameter('nom', '%' . $data['nom'] . '%');
            }
            if ($data['type']) {
                $qb->andWhere('c.type LIKE :type')->setParameter('type', '%' . $data['type'] . '%');
            }
            if ($data['nombreContactsMin']) {
                $qb->andWhere('c.nombreContacts >= :contactsMin')->setParameter('contactsMin', $data['nombreContactsMin']);
            }
            if ($data['nombreContactsMax']) {
                $qb->andWhere('c.nombreContacts <= :contactsMax')->setParameter('contactsMax', $data['nombreContactsMax']);
            }
            if ($data['prixUnitaireMin']) {
                $qb->andWhere('c.prixUnitaire >= :prixMin')->setParameter('prixMin', $data['prixUnitaireMin']);
            }
            if ($data['prixUnitaireMax']) {
                $qb->andWhere('c.prixUnitaire <= :prixMax')->setParameter('prixMax', $data['prixUnitaireMax']);
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

        return $this->render('catalogue_modele_connecteurs/list.html.twig', [
            'connecteurs' => $connecteurs,
            'filter_form' => $filterForm->createView(),
            'selected_ids' => $selectedIds,
        ]);
    }

    /**
     * @Route("/new", name="catalogue_modele_connecteurs_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $connecteur = new CatalogueModeleConnecteurs();
        $form = $this->createForm(CatalogueModeleConnecteursType::class, $connecteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($connecteur);
            $entityManager->flush();

            $this->addFlash('success', 'Modèle de connecteur ajouté avec succès.');
            return $this->redirectToRoute('catalogue_modele_connecteurs_list');
        }

        return $this->render('catalogue_modele_connecteurs/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="catalogue_modele_connecteurs_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CatalogueModeleConnecteurs $connecteur): Response
    {
        $form = $this->createForm(CatalogueModeleConnecteursType::class, $connecteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Modèle de connecteur modifié avec succès.');
            return $this->redirectToRoute('catalogue_modele_connecteurs_list');
        }

        return $this->render('catalogue_modele_connecteurs/edit.html.twig', [
            'connecteur' => $connecteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="catalogue_modele_connecteurs_delete", methods={"POST"})
     */
    public function delete(Request $request, CatalogueModeleConnecteurs $connecteur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$connecteur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($connecteur);
            $entityManager->flush();

            $this->addFlash('success', 'Modèle de connecteur supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('catalogue_modele_connecteurs_list');
    }
}
