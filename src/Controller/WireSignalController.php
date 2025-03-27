<?php
namespace App\Controller;

use App\Entity\WireSignal;
use App\Entity\Projet;
use App\Form\WireSignalFilterType;
use App\Form\WireSignalType;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Knp\Component\Pager\PaginatorInterface;

class WireSignalController extends BaseController
{
    #[Route('/projet/{projetId}/signaux', name: 'wire_signal_list', methods: ['GET', 'POST'])]
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

        // Vérification des droits d'accès au projet (supposé hérité de BaseController)
        $this->checkProjectAccess($projet, $em);

        $session = $request->getSession();
        $selectedIds = $session->get('selected_signals_' . $projetId, []);

        // Gestion des suppressions multiples via POST
        if ($request->isMethod('POST') && $this->isGranted('CAN_EDIT_SIGNALS', $projet)) {
            if (empty($selectedIds)) {
                $this->addFlash('warning', 'Aucun signal sélectionné pour la suppression.');
            } else {
                $itemsToDelete = $em->getRepository(WireSignal::class)->findBy(['id' => $selectedIds, 'projet' => $projet]);
                if (empty($itemsToDelete)) {
                    $this->addFlash('error', 'Aucun signal valide trouvé pour la suppression.');
                } else {
                    foreach ($itemsToDelete as $item) {
                        $em->remove($item);
                    }
                    $em->flush();
                    $this->addFlash('success', 'Signaux sélectionnés supprimés avec succès.');
                    $session->set('selected_signals_' . $projetId, []); // Réinitialiser après suppression
                }
            }
            return $this->redirectToRoute('wire_signal_list', ['projetId' => $projetId]);
        }

        // Mise à jour des sélections via GET (AJAX)
        if ($request->query->has('toggle_selection') && $this->isGranted('CAN_EDIT_SIGNALS', $projet)) {
            $itemId = $request->query->get('item_id');
            if (in_array($itemId, $selectedIds)) {
                $selectedIds = array_diff($selectedIds, [$itemId]);
            } else {
                $selectedIds[] = $itemId;
            }
            $session->set('selected_signals_' . $projetId, $selectedIds);
            return $this->json(['success' => true, 'selected' => $selectedIds]);
        }

        // Formulaire de filtre et pagination
        $filterForm = $formFactory->create(WireSignalFilterType::class);
        $filterForm->handleRequest($request);

        $qb = $em->getRepository(WireSignal::class)->createQueryBuilder('s')
            ->where('s.projet = :projet')
            ->setParameter('projet', $projet);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();
            if ($data['nom']) {
                $qb->andWhere('s.nom LIKE :nom')->setParameter('nom', '%' . $data['nom'] . '%');
            }
            if ($data['type']) {
                $qb->andWhere('s.type = :type')->setParameter('type', $data['type']);
            }
            if ($data['details']) {
                $qb->andWhere('s.details LIKE :details')->setParameter('details', '%' . $data['details'] . '%');
            }
        }

        $signaux = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10,
            [
                'defaultSortFieldName' => 's.nom',
                'defaultSortDirection' => 'asc',
            ]
        );

        return $this->render('wire_signal/list.html.twig', [
            'projet' => $projet,
            'signaux' => $signaux,
            'filter_form' => $filterForm->createView(),
            'selected_ids' => $selectedIds,
        ]);
    }

#[Route('/projet/{projetId}/signaux/new', name: 'wire_signal_new', methods: ['GET', 'POST'])]
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

        $this->denyAccessUnlessGranted('CAN_EDIT_SIGNALS', $projet);

        $signal = new WireSignal();
        $signal->setProjet($projet); // Lier le signal au projet dès la création
        $form = $formFactory->create(WireSignalType::class, $signal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($signal);
            $em->flush();
            $this->addFlash('success', 'Signal ajouté avec succès.');
            return $this->redirectToRoute('wire_signal_list', ['projetId' => $projet->getId()]);
        }

        return $this->render('wire_signal/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

	#[Route('/projet/{projetId}/signaux/{id}/edit', name: 'wire_signal_edit', methods: ['GET', 'POST'])]
	public function edit(
	    int $projetId,
	    int $id,
	    ProjetRepository $projetRepository,
	    Request $request,
	    EntityManagerInterface $em,
	    FormFactoryInterface $formFactory
	): Response {
	    $projet = $projetRepository->find($projetId);
	    if (!$projet) {
		throw $this->createNotFoundException('Projet non trouvé');
	    }

	    $signal = $em->getRepository(WireSignal::class)->find($id);
	    if (!$signal || $signal->getProjet()->getId() !== $projetId) {
		throw $this->createNotFoundException('Signal non trouvé ou non associé à ce projet');
	    }

	    $this->denyAccessUnlessGranted('CAN_EDIT_SIGNALS', $projet);

	    $form = $formFactory->create(WireSignalType::class, $signal);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
		$em->flush();
		$this->addFlash('success', 'Signal modifié avec succès.');
		return $this->redirectToRoute('wire_signal_list', ['projetId' => $projet->getId()]);
	    }

	    return $this->render('wire_signal/edit.html.twig', [
		'projet' => $projet,
		'signal' => $signal,
		'form' => $form->createView(),
	    ]);
	}

	#[Route('/projet/{projetId}/signaux/{id}/delete', name: 'wire_signal_delete', methods: ['POST'])]
	public function delete(
	    int $projetId,
	    int $id,
	    ProjetRepository $projetRepository,
	    Request $request,
	    EntityManagerInterface $em
	): Response {
	    $projet = $projetRepository->find($projetId);
	    if (!$projet) {
		throw $this->createNotFoundException('Projet non trouvé');
	    }

	    $signal = $em->getRepository(WireSignal::class)->find($id);
	    if (!$signal || $signal->getProjet()->getId() !== $projetId) {
		throw $this->createNotFoundException('Signal non trouvé ou non associé à ce projet');
	    }

	    $this->denyAccessUnlessGranted('CAN_EDIT_SIGNALS', $projet);

	    if ($this->isCsrfTokenValid('delete_' . $signal->getId(), $request->request->get('_token'))) {
		$em->remove($signal);
		$em->flush();
		$this->addFlash('success', 'Signal supprimé avec succès.');
	    } else {
		$this->addFlash('danger', 'Erreur de sécurité lors de la suppression du signal.');
	    }

	    return $this->redirectToRoute('wire_signal_list', ['projetId' => $projet->getId()]);
	}


}
