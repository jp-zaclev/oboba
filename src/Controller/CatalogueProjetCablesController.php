<?php
namespace App\Controller;

use App\Entity\CatalogueProjetCables;
use App\Entity\Projet;
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
    #[Route('/projet/{projetId}/catalogue-cables', name: 'catalogue_projet_cables_list', methods: ['GET'])]
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

        $filterForm = $formFactory->create(CatalogueProjetCablesFilterType::class);
        $filterForm->handleRequest($request);

        $qb = $em->getRepository(CatalogueProjetCables::class)->createQueryBuilder('c')
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
            if ($data['nombreConducteursMax'] !== null) {
                $qb->andWhere('c.nombreConducteursMax = :nombreConducteursMax')
                   ->setParameter('nombreConducteursMax', $data['nombreConducteursMax']);
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
            'catalogues' => $catalogues,
            'filter_form' => $filterForm->createView(),
        ]);
    }
    
    
    #[Route('/projet/{projetId}/catalogue-cables/new', name: 'catalogue_projet_cables_new', methods: ['GET', 'POST'])]
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

        $catalogue = new CatalogueProjetCables();
        $catalogue->setProjet($projet);
        $form = $formFactory->create(CatalogueProjetCablesType::class, $catalogue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($catalogue);
            $em->flush();
            $this->addFlash('success', 'Câble ajouté au catalogue avec succès');
            return $this->redirectToRoute('catalogue_projet_cables_list', ['projetId' => $projet->getId()]);
        }

        return $this->render('catalogue_projet_cables/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/catalogue-cables/{id}/edit', name: 'catalogue_projet_cables_edit', methods: ['GET', 'POST'])]
    public function edit(
        CatalogueProjetCables $catalogue,
        Request $request,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory
    ): Response {
        $projet = $catalogue->getProjet();
        $this->denyAccessUnlessGranted('CAN_EDIT_CABLES', $projet);

        $form = $formFactory->create(CatalogueProjetCablesType::class, $catalogue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Câble du catalogue modifié avec succès');
            return $this->redirectToRoute('catalogue_projet_cables_list', ['projetId' => $projet->getId()]);
        }

        return $this->render('catalogue_projet_cables/edit.html.twig', [
            'projet' => $projet,
            'catalogue' => $catalogue,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/catalogue-cables/{id}/delete', name: 'catalogue_projet_cables_delete', methods: ['POST'])]
    public function delete(
        CatalogueProjetCables $catalogue,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $projet = $catalogue->getProjet();
        $this->denyAccessUnlessGranted('CAN_EDIT_CABLES', $projet);

        if (!$this->isCsrfTokenValid('delete_' . $catalogue->getId(), $request->request->get('_token'))) {
            throw new AccessDeniedException('Token CSRF invalide');
        }

        $em->remove($catalogue);
        $em->flush();
        $this->addFlash('success', 'Câble supprimé du catalogue avec succès');
        return $this->redirectToRoute('catalogue_projet_cables_list', ['projetId' => $projet->getId()]);
    }
}
