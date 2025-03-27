<?php
namespace App\Controller;

use App\Entity\Localisation;
use App\Form\LocalisationType;
use App\Form\LocalisationFilterType;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Knp\Component\Pager\PaginatorInterface;

class LocalisationController extends AbstractController
{
#[Route('/projet/{projetId}/emplacements', name: 'localisation_list', methods: ['GET'])]
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

        $filterForm = $this->createForm(LocalisationFilterType::class, null, ['projet_id' => $projetId]);
        $filterForm->handleRequest($request);

        $qb = $em->getRepository(Localisation::class)->createQueryBuilder('l')
            ->where('l.projet = :projet')
            ->setParameter('projet', $projet);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();

            // Filtre sur le nom
            if ($data['nom']) {
                $qb->andWhere('l.nom LIKE :nom')
                   ->setParameter('nom', '%' . $data['nom'] . '%');
            }

            // Fonction pour parser les expressions numériques
            $parseNumericFilter = function ($value, $field) use ($qb) {
                if (!$value) {
                    return;
                }

                // Supprimer les espaces
                $value = trim($value);

                // Cas 1 : "<valeur" (ex: "<12")
                if (preg_match('/^<(\d*\.?\d+)$/', $value, $matches)) {
                    $qb->andWhere("l.$field < :{$field}_lt")
                       ->setParameter("{$field}_lt", (float)$matches[1]);
                }
                // Cas 2 : ">valeur" (ex: ">3.5")
                elseif (preg_match('/^>(\d*\.?\d+)$/', $value, $matches)) {
                    $qb->andWhere("l.$field > :{$field}_gt")
                       ->setParameter("{$field}_gt", (float)$matches[1]);
                }
                // Cas 3 : "<=valeur" (ex: "<=12")
                elseif (preg_match('/^<=(\d*\.?\d+)$/', $value, $matches)) {
                    $qb->andWhere("l.$field <= :{$field}_lte")
                       ->setParameter("{$field}_lte", (float)$matches[1]);
                }
                // Cas 4 : ">=valeur" (ex: ">=3.5")
                elseif (preg_match('/^>=(\d*\.?\d+)$/', $value, $matches)) {
                    $qb->andWhere("l.$field >= :{$field}_gte")
                       ->setParameter("{$field}_gte", (float)$matches[1]);
                }
                // Cas 5 : "min<max" (ex: "3<7")
                elseif (preg_match('/^(\d*\.?\d+)<(\d*\.?\d+)$/', $value, $matches)) {
                    $qb->andWhere("l.$field BETWEEN :{$field}_min AND :{$field}_max")
                       ->setParameter("{$field}_min", (float)$matches[1])
                       ->setParameter("{$field}_max", (float)$matches[2]);
                }
                // Cas 6 : Valeur exacte (ex: "5")
                elseif (is_numeric($value)) {
                    $qb->andWhere("l.$field = :{$field}_eq")
                       ->setParameter("{$field}_eq", (float)$value);
                }
            };

            // Appliquer les filtres pour x, y, z
            $parseNumericFilter($data['x'], 'x');
            $parseNumericFilter($data['y'], 'y');
            $parseNumericFilter($data['z'], 'z');
        }

        $emplacements = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10,
            ['defaultSortFieldName' => 'l.nom', 'defaultSortDirection' => 'asc']
        );

        return $this->render('localisation/list.html.twig', [
            'projet' => $projet,
            'emplacements' => $emplacements,
            'filter_form' => $filterForm->createView(),
        ]);
    }

    #[Route('/projet/{projetId}/localisations/new', name: 'localisation_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        int $projetId,
        EntityManagerInterface $em,
        ProjetRepository $projetRepository
    ): Response {
        $projet = $projetRepository->find($projetId);
        if (!$projet) {
            throw $this->createNotFoundException('Projet non trouvé');
        }

        $localisation = new Localisation();
        $localisation->setProjet($projet);
        $form = $this->createForm(LocalisationType::class, $localisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($localisation);
            $em->flush();
            $this->addFlash('success', 'Localisation ajoutée avec succès');
            return $this->redirectToRoute('localisation_list', ['projetId' => $projetId]);
        }

        return $this->render('localisation/new.html.twig', [
            'form' => $form->createView(),
            'projet' => $projet,
        ]);
    }

    #[Route('/projet/{projetId}/localisations/{id}/edit', name: 'localisation_edit', methods: ['GET', 'POST'])]
    public function edit(
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

        $localisation = $em->getRepository(Localisation::class)->find($id);
        if (!$localisation || $localisation->getProjet()->getId() !== $projetId) {
            throw $this->createNotFoundException('Localisation non trouvée pour ce projet');
        }

        $form = $this->createForm(LocalisationType::class, $localisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Localisation modifiée avec succès');
            return $this->redirectToRoute('localisation_list', ['projetId' => $projetId]);
        }

        return $this->render('localisation/edit.html.twig', [
            'form' => $form->createView(),
            'projet' => $projet,
        ]);
    }

    #[Route('/projet/{projetId}/localisations/{id}', name: 'localisation_delete', methods: ['POST'])]
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

        $localisation = $em->getRepository(Localisation::class)->find($id);
        if (!$localisation || $localisation->getProjet()->getId() !== $projetId) {
            throw $this->createNotFoundException('Localisation non trouvée pour ce projet');
        }

        if ($this->isCsrfTokenValid('delete_' . $localisation->getId(), $request->request->get('_token'))) {
            $em->remove($localisation);
            $em->flush();
            $this->addFlash('success', 'Localisation supprimée avec succès');
        } else {
            $this->addFlash('error', 'Token invalide, suppression annulée');
        }

        return $this->redirectToRoute('localisation_list', ['projetId' => $projetId]);
    }
}
