<?php
namespace App\Controller;

use App\Entity\Bornier;
use App\Form\BornierType;
use App\Form\BornierFilterType;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Knp\Component\Pager\PaginatorInterface;

class BornierController extends BaseController
{
#[Route('/projet/{projetId}/borniers', name: 'bornier_list', methods: ['GET'])]
    public function list(
        int $projetId,
        ProjetRepository $projetRepository,
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ): Response {
        $projet = $projetRepository->find($projetId);
        if (!$projet) {
            throw $this->createNotFoundException('Projet non trouvé');
        }

        $filterForm = $this->createForm(BornierFilterType::class, null, ['projet_id' => $projetId]);
        $filterForm->handleRequest($request);

        $qb = $em->getRepository(Bornier::class)->createQueryBuilder('b')
            ->leftJoin('b.catalogueProjetBorniers', 'cat')
            ->leftJoin('b.localisation', 'loc')
            ->addSelect('cat', 'loc')
            ->where('b.projet = :projet')
            ->setParameter('projet', $projet);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();
            if ($data['nom']) {
                $qb->andWhere('b.nom LIKE :nom')->setParameter('nom', '%' . $data['nom'] . '%');
            }
            if ($data['nombreBornes']) {
                $qb->andWhere('cat.nombreBornes = :nombreBornes')->setParameter('nombreBornes', $data['nombreBornes']);
            }
            if ($data['caracteristiques']) {
                $qb->andWhere('cat.caracteristiques LIKE :caracteristiques')->setParameter('caracteristiques', '%' . $data['caracteristiques'] . '%');
            }
            if ($data['catalogueProjetBorniers']) {
                $qb->andWhere('b.catalogueProjetBorniers = :catalogue')->setParameter('catalogue', $data['catalogueProjetBorniers']);
            }
            if ($data['localisation']) {
                $qb->andWhere('b.localisation = :localisation')->setParameter('localisation', $data['localisation']);
            }
        }

        $borniers = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10,
            ['defaultSortFieldName' => 'b.nom', 'defaultSortDirection' => 'asc']
        );

        return $this->render('bornier/list.html.twig', [
            'projet' => $projet,
            'borniers' => $borniers,
            'filter_form' => $filterForm->createView(),
        ]);
    }
    #[Route('/projet/{projetId}/borniers/new', name: 'bornier_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        int $projetId,
        EntityManagerInterface $em,
        ProjetRepository $projetRepository,
        FormFactoryInterface $formFactory
    ): Response {
        $projet = $projetRepository->find($projetId);
        if (!$projet) {
            throw $this->createNotFoundException('Projet non trouvé');
        }

        $this->checkProjectAccess($projet, $em);

        $bornier = new Bornier();
        $bornier->setProjet($projet);
        $form = $formFactory->create(BornierType::class, $bornier, ['projet_id' => $projetId]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($bornier);
            $em->flush();
            $this->addFlash('success', 'Bornier ajouté avec succès');
            return $this->redirectToRoute('bornier_list', ['projetId' => $projetId]);
        }

        return $this->render('bornier/new.html.twig', [
            'form' => $form->createView(),
            'projet' => $projet,
        ]);
    }

    #[Route('/projet/{projetId}/borniers/export', name: 'bornier_export_csv', methods: ['GET'])]
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

        $this->checkProjectAccess($projet, $em);

        $filterForm = $formFactory->create(BornierFilterType::class, null, ['projet_id' => $projetId]);
        $filterForm->handleRequest($request);

        $qb = $em->getRepository(Bornier::class)->createQueryBuilder('b')
            ->leftJoin('b.catalogueProjetBorniers', 'cat')
            ->where('b.projet = :projet')
            ->setParameter('projet', $projet);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();

            if ($data['nom']) {
                $qb->andWhere('b.nom LIKE :nom')
                   ->setParameter('nom', '%' . $data['nom'] . '%');
            }
            if ($data['nombreBornes'] !== null) {
                $qb->andWhere('cat.nombreBornes = :nombreBornes')
                   ->setParameter('nombreBornes', $data['nombreBornes']);
            }
            if ($data['caracteristiques']) {
                $qb->andWhere('cat.caracteristiques LIKE :caracteristiques')
                   ->setParameter('caracteristiques', '%' . $data['caracteristiques'] . '%');
            }
            if ($data['catalogueProjetBorniers']) {
                $qb->andWhere('b.catalogueProjetBorniers = :catalogue')
                   ->setParameter('catalogue', $data['catalogueProjetBorniers']);
            }
        }

        $borniers = $qb->getQuery()->getResult();

        $csvContent = [];
        $csvContent[] = ['Nom', 'Catalogue Projet', 'Nombre de Bornes', 'Caractéristiques', 'Prix Unitaire'];

        foreach ($borniers as $bornier) {
            $catalogue = $bornier->getCatalogueProjetBorniers();
            $csvContent[] = [
                $bornier->getNom(),
                $catalogue ? $catalogue->getNom() : 'N/A',
                $catalogue ? $catalogue->getNombreBornes() : 'N/A',
                $catalogue ? $catalogue->getCaracteristiques() : 'N/A',
                $catalogue ? $catalogue->getPrixUnitaire() : 'N/A',
            ];
        }

        $response = new Response($this->arrayToCsv($csvContent));
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'borniers_' . $projetId . '_' . date('Ymd_His') . '.csv'
        );
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    #[Route('/projet/{projetId}/borniers/{id}/edit', name: 'bornier_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $projetId,
        int $id,
        Request $request,
        EntityManagerInterface $em,
        ProjetRepository $projetRepository,
        FormFactoryInterface $formFactory
    ): Response {
        $projet = $projetRepository->find($projetId);
        if (!$projet) {
            throw $this->createNotFoundException('Projet non trouvé');
        }

        $bornier = $em->getRepository(Bornier::class)->find($id);
        if (!$bornier || $bornier->getProjet()->getId() !== $projetId) {
            throw $this->createNotFoundException('Bornier non trouvé pour ce projet');
        }

        $this->checkProjectAccess($projet, $em);

        $form = $formFactory->create(BornierType::class, $bornier, ['projet_id' => $projetId]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Bornier modifié avec succès');
            return $this->redirectToRoute('bornier_list', ['projetId' => $projetId]);
        }

        return $this->render('bornier/edit.html.twig', [
            'form' => $form->createView(),
            'projet' => $projet,
        ]);
    }

    #[Route('/projet/{projetId}/borniers/{id}', name: 'bornier_delete', methods: ['POST'])]
    public function delete(
        int $projetId,
        int $id,
        Request $request,
        EntityManagerInterface $em,
        ProjetRepository $projetRepository
    ): Response {
        $projet = $projetRepository->find($projetId);
        if (!$projet) {
            throw $this->createNotFoundException('Projet non trouvé');
        }

        $bornier = $em->getRepository(Bornier::class)->find($id);
        if (!$bornier || $bornier->getProjet()->getId() !== $projetId) {
            throw $this->createNotFoundException('Bornier non trouvé pour ce projet');
        }

        $this->checkProjectAccess($projet, $em);

        if ($this->isCsrfTokenValid('delete_' . $bornier->getId(), $request->request->get('_token'))) {
            $em->remove($bornier);
            $em->flush();
            $this->addFlash('success', 'Bornier supprimé avec succès');
        } else {
            $this->addFlash('error', 'Token invalide, suppression annulée');
        }

        return $this->redirectToRoute('bornier_list', ['projetId' => $projetId]);
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
