<?php
namespace App\Controller;

use App\Entity\CatalogueModeleConnecteurs;
use App\Entity\CatalogueContact;
use App\Form\CatalogueModeleConnecteursFilterType;
use App\Form\CatalogueModeleConnecteursType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/catalogue/modele/connecteurs")
 * @IsGranted("ROLE_ADMIN")
 */
class CatalogueModeleConnecteursController extends BaseController
{
    use FilterHelperTrait;

    #[Route("/", name: "catalogue_modele_connecteurs_list", methods: ["GET", "POST"])]
    public function list(
        Request $request,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        PaginatorInterface $paginator
    ): Response {
        $session = $request->getSession();
        $selectedIds = $session->get('selected_modele_connecteurs', []);

        if ($request->isMethod('POST')) {
            if (empty($selectedIds)) {
                $this->addFlash('warning', 'Aucun élément sélectionné pour la suppression.');
            } else {
                $itemsToDelete = $em->getRepository(CatalogueModeleConnecteurs::class)->findBy(['id' => $selectedIds]);
                if (empty($itemsToDelete)) {
                    $this->addFlash('error', 'Aucun élément valide trouvé pour la suppression.');
                } else {
                    foreach ($itemsToDelete as $item) {
                        $em->remove($item);
                    }
                    $em->flush();
                    $this->addFlash('success', 'Modèles de connecteurs sélectionnés supprimés avec succès.');
                    $session->set('selected_modele_connecteurs', []);
                }
            }
            return $this->redirectToRoute('catalogue_modele_connecteurs_list');
        }

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

        $filterForm = $formFactory->create(CatalogueModeleConnecteursFilterType::class);
        $filterForm->handleRequest($request);

        $qb = $em->getRepository(CatalogueModeleConnecteurs::class)->createQueryBuilder('c');

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();
            if ($data['nom']) {
                $qb->andWhere('c.nom LIKE :nom')->setParameter('nom', '%' . $data['nom'] . '%');
            }
            if ($data['type']) {
                $qb->andWhere('c.type LIKE :type')->setParameter('type', '%' . $data['type'] . '%');
            }
            if ($data['nombreContacts'] !== null && $data['nombreContacts'] !== '') {
                $this->applyNumericFilter($qb, 'c.nombreContacts', $data['nombreContacts'], 'contacts');
            }
            if ($data['prixUnitaire'] !== null && $data['prixUnitaire'] !== '') {
                $this->applyNumericFilter($qb, 'c.prixUnitaire', $data['prixUnitaire'], 'prix');
            }
        }

        $items = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10,
            [
                'defaultSortFieldName' => 'c.nom',
                'defaultSortDirection' => 'asc',
            ]
        );

        return $this->render('catalogue_modele_connecteurs/list.html.twig', [
            'items' => $items,
            'filter_form' => $filterForm->createView(),
            'selected_ids' => $selectedIds,
        ]);
    }

	#[Route("/new", name: "catalogue_modele_connecteurs_new", methods: ["GET", "POST"])]
	public function new(Request $request, EntityManagerInterface $em): Response
	{
	    $connecteur = new CatalogueModeleConnecteurs();
	    $form = $this->createForm(CatalogueModeleConnecteursType::class, $connecteur);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
		// Pré-remplir les contacts si la collection est vide
		if ($connecteur->getCatalogueContacts()->isEmpty()) {
		    $nombreContacts = $connecteur->getNombreContacts();
		    for ($i = 1; $i <= $nombreContacts; $i++) {
		        $contact = new CatalogueContact();
		        $contact->setIdentifiant("$i");
		        $contact->setType('emission_reception'); // Valeur par défaut, ajustable
		        $connecteur->addCatalogueContact($contact);
		    }
		}
		$em->persist($connecteur);
		$em->flush();
		$this->addFlash('success', 'Modèle de connecteur ajouté avec succès.');
		return $this->redirectToRoute('catalogue_modele_connecteurs_edit', ['id' => $connecteur->getId()]);
	    }

	    return $this->render('catalogue_modele_connecteurs/new.html.twig', [
		'form' => $form->createView(),
	    ]);
	}
	#[Route('/{id}/edit', name: 'catalogue_modele_connecteurs_edit', methods: ['GET', 'POST'])]
	public function edit(Request $request, CatalogueModeleConnecteurs $connecteur, EntityManagerInterface $em): Response
	{
	    $form = $this->createForm(CatalogueModeleConnecteursType::class, $connecteur);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
		$em->flush();
		$this->addFlash('success', 'Modèle de connecteur modifié avec succès.');
		return $this->redirectToRoute('catalogue_modele_connecteurs_list');
	    }

	    return $this->render('catalogue_modele_connecteurs/edit.html.twig', [
		'connecteur' => $connecteur,
		'form' => $form->createView(),
	    ]);
	}

    #[Route("/{id}", name: "catalogue_modele_connecteurs_delete", methods: ["POST"])]
    public function delete(CatalogueModeleConnecteurs $connecteur, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $connecteur->getId(), $request->request->get('_token'))) {
            $em->remove($connecteur);
            $em->flush();
            $this->addFlash('success', 'Modèle de connecteur supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('catalogue_modele_connecteurs_list');
    }
}
