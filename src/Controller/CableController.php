<?php
namespace App\Controller;

use App\Entity\Cable;
use App\Entity\Projet;
use App\Form\CableType;
use App\Form\CableFilterType;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Knp\Component\Pager\PaginatorInterface;

class CableController extends AbstractController
{
    #[Route('/projet/{projetId}/cables', name: 'cable_list', methods: ['GET'])]
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

        $this->denyAccessUnlessGranted('CAN_EDIT_CABLES', $projet);

        $filterForm = $formFactory->create(CableFilterType::class, null, ['projet_id' => $projet->getId()]);
        $filterForm->handleRequest($request);

        $qb = $em->getRepository(Cable::class)->createQueryBuilder('c')
            ->where('c.projet = :projet')
            ->setParameter('projet', $projet);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();

            if ($data['nom']) {
                $qb->andWhere('c.nom LIKE :nom')
                   ->setParameter('nom', '%' . $data['nom'] . '%');
            }
            if ($data['longueurMin'] !== null) {
                $qb->andWhere('c.longueur >= :longueurMin')
                   ->setParameter('longueurMin', $data['longueurMin']);
            }
            if ($data['longueurMax'] !== null) {
                $qb->andWhere('c.longueur <= :longueurMax')
                   ->setParameter('longueurMax', $data['longueurMax']);
            }
            if ($data['catalogueProjetCables']) {
                $qb->andWhere('c.catalogueProjetCables = :catalogue')
                   ->setParameter('catalogue', $data['catalogueProjetCables']);
            }
        }

        $cables = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('cable/list.html.twig', [
            'projet' => $projet,
            'cables' => $cables,
            'filter_form' => $filterForm->createView(),
        ]);
    }

    #[Route('/projet/{projetId}/cables/export', name: 'cable_export_csv', methods: ['GET'])]
    public function exportCsv(
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

        $filterForm = $formFactory->create(CableFilterType::class, null, ['projet_id' => $projet->getId()]);
        $filterForm->handleRequest($request);

        $qb = $em->getRepository(Cable::class)->createQueryBuilder('c')
            ->where('c.projet = :projet')
            ->setParameter('projet', $projet);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();

            if ($data['nom']) {
                $qb->andWhere('c.nom LIKE :nom')
                   ->setParameter('nom', '%' . $data['nom'] . '%');
            }
            if ($data['longueurMin'] !== null) {
                $qb->andWhere('c.longueur >= :longueurMin')
                   ->setParameter('longueurMin', $data['longueurMin']);
            }
            if ($data['longueurMax'] !== null) {
                $qb->andWhere('c.longueur <= :longueurMax')
                   ->setParameter('longueurMax', $data['longueurMax']);
            }
            if ($data['catalogueProjetCables']) {
                $qb->andWhere('c.catalogueProjetCables = :catalogue')
                   ->setParameter('catalogue', $data['catalogueProjetCables']);
            }
        }

        $cables = $qb->getQuery()->getResult();

        $csvContent = [];
        $csvContent[] = ['Nom', 'Longueur', 'Catalogue Projet'];

        foreach ($cables as $cable) {
            $csvContent[] = [
                $cable->getNom(),
                $cable->getLongueur(),
                $cable->getCatalogueProjetCables() ? $cable->getCatalogueProjetCables()->getNom() : 'N/A',
            ];
        }

        $response = new Response($this->arrayToCsv($csvContent));
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'cables_' . $projet->getId() . '_' . date('Ymd_His') . '.csv'
        );
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    #[Route('/projet/{projetId}/cables/new', name: 'cable_new', methods: ['GET', 'POST'])]
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

        $cable = new Cable();
        $cable->setProjet($projet);
        $form = $formFactory->create(CableType::class, $cable, ['projet_id' => $projet->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($cable);
            $em->flush();
            $this->addFlash('success', 'Câble ajouté avec succès');
            return $this->redirectToRoute('cable_list', ['projetId' => $projet->getId()]);
        }

        return $this->render('cable/new.html.twig', [
            'form' => $form->createView(),
            'projet' => $projet,
        ]);
    }

    #[Route('/cable/{id}/edit', name: 'cable_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Cable $cable,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory
    ): Response {
        $projet = $cable->getProjet();
        $this->denyAccessUnlessGranted('CAN_EDIT_CABLES', $projet);

        $form = $formFactory->create(CableType::class, $cable, ['projet_id' => $projet->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Câble modifié avec succès');
            return $this->redirectToRoute('cable_list', ['projetId' => $projet->getId()]);
        }

        return $this->render('cable/edit.html.twig', [
            'form' => $form->createView(),
            'projet' => $projet,
            'cable' => $cable,
        ]);
    }

    #[Route('/cable/{id}/delete', name: 'cable_delete', methods: ['POST'])]
    public function delete(Request $request, Cable $cable, EntityManagerInterface $em): Response
    {
        $projet = $cable->getProjet();
        $this->denyAccessUnlessGranted('CAN_EDIT_CABLES', $projet);

        if (!$this->isCsrfTokenValid('delete_' . $cable->getId(), $request->request->get('_token'))) {
            throw new AccessDeniedException('Token CSRF invalide');
        }

        $em->remove($cable);
        $em->flush();
        $this->addFlash('success', 'Câble supprimé avec succès');
        return $this->redirectToRoute('cable_list', ['projetId' => $projet->getId()]);
    }

    private function arrayToCsv(array $data): string
    {
        $output = fopen('php://temp', 'r+');
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        return $csv;
    }
}
