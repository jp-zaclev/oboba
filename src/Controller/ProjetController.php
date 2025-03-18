<?php
namespace App\Controller;

use App\Entity\Projet;
use App\Entity\ProjetUtilisateur;
use App\Entity\Utilisateur;
use App\Form\ProjetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/projets')]
class ProjetController extends AbstractController
{
    #[Route('', name: 'projet_list', methods: ['GET'])]
    public function list(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $projets = $em->getRepository(Projet::class)->findAll();

        return $this->render('projet/list.html.twig', [
            'projets' => $projets,
        ]);
    }

    #[Route('/new', name: 'projet_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Ajouter le propriétaire
            $proprietaire = $form->get('proprietaire')->getData();
            $puProprietaire = new ProjetUtilisateur();
            $puProprietaire->setProjet($projet)
                           ->setUtilisateur($proprietaire)
                           ->setRole('proprietaire');
            $projet->addProjetUtilisateur($puProprietaire);

            // Ajouter les concepteurs
            foreach ($form->get('concepteurs')->getData() as $concepteur) {
                $pu = new ProjetUtilisateur();
                $pu->setProjet($projet)->setUtilisateur($concepteur)->setRole('concepteur');
                $projet->addProjetUtilisateur($pu);
            }

            // Ajouter les lecteurs
            foreach ($form->get('lecteurs')->getData() as $lecteur) {
                $pu = new ProjetUtilisateur();
                $pu->setProjet($projet)->setUtilisateur($lecteur)->setRole('lecteur');
                $projet->addProjetUtilisateur($pu);
            }

            $projet->setDateHeureDerniereModification(new \DateTime());
            $em->persist($projet);
            $em->flush();

            $this->addFlash('success', 'Projet créé avec succès.');
            return $this->redirectToRoute('projet_list');
        }

        return $this->render('projet/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'projet_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Projet $projet, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Supprimer les anciens ProjetUtilisateur
            foreach ($projet->getProjetUtilisateurs() as $pu) {
                $em->remove($pu);
            }
            $projet->getProjetUtilisateurs()->clear();

            // Ajouter le nouveau propriétaire
            $proprietaire = $form->get('proprietaire')->getData();
            $puProprietaire = new ProjetUtilisateur();
            $puProprietaire->setProjet($projet)
                           ->setUtilisateur($proprietaire)
                           ->setRole('proprietaire');
            $projet->addProjetUtilisateur($puProprietaire);

            // Ajouter les nouveaux concepteurs
            foreach ($form->get('concepteurs')->getData() as $concepteur) {
                $pu = new ProjetUtilisateur();
                $pu->setProjet($projet)->setUtilisateur($concepteur)->setRole('concepteur');
                $projet->addProjetUtilisateur($pu);
            }

            // Ajouter les nouveaux lecteurs
            foreach ($form->get('lecteurs')->getData() as $lecteur) {
                $pu = new ProjetUtilisateur();
                $pu->setProjet($projet)->setUtilisateur($lecteur)->setRole('lecteur');
                $projet->addProjetUtilisateur($pu);
            }

            $projet->setDateHeureDerniereModification(new \DateTime());
            $em->flush();

            $this->addFlash('success', 'Projet modifié avec succès.');
            return $this->redirectToRoute('projet_list');
        }

        return $this->render('projet/edit.html.twig', [
            'form' => $form->createView(),
            'projet' => $projet,
        ]);
    }

    #[Route('/{id}', name: 'projet_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Projet $projet, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->request->get('_token'))) {
            $em->remove($projet);
            $em->flush();
            $this->addFlash('success', 'Projet supprimé avec succès.');
        }

        return $this->redirectToRoute('projet_list');
    }
    
    #[Route('/mes-projets', name: 'projet_mes_projets', methods: ['GET'])]
    public function mesProjets(EntityManagerInterface $em): Response
    {
        $utilisateur = $this->getUser();
        if (!$utilisateur) {
            return $this->redirectToRoute('login');
        }

        $projets = $em->getRepository(Projet::class)->findByUtilisateur($utilisateur);

        return $this->render('projet/mes_projets.html.twig', [
            'projets' => $projets,
        ]);
    }
}
