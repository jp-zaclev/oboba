<?php
namespace App\Controller;

use App\Entity\CatalogueProjetConnecteurs;
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
    #[Route('/projet/{projetId}/catalogue-connecteurs', name: 'catalogue_projet_connecteurs_list', methods: ['GET'])]
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

        $this->denyAccessUnlessGranted('CAN_EDIT_CABLES', $projet);

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
        $this->denyAccessUnlessGranted('CAN_EDIT_CABLES', $projet);

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
        $this->denyAccessUnlessGranted('CAN_EDIT_CABLES', $projet);

        if (!$this->isCsrfTokenValid('delete_' . $connecteur->getId(), $request->request->get('_token'))) {
            throw new AccessDeniedException('Token CSRF invalide');
        }

        $em->remove($connecteur);
        $em->flush();
        $this->addFlash('success', 'Connecteur supprimé du catalogue avec succès');
        return $this->redirectToRoute('catalogue_projet_connecteurs_list', ['projetId' => $projet->getId()]);
    }
}
