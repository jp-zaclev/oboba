<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Entity\ProjetUtilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateurs/gestion', name: 'utilisateurs_gestion')]
    public function gestion(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $qb = $em->getRepository(Utilisateur::class)->createQueryBuilder('u');

        $utilisateurs = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10,
            [
                'defaultSortFieldName' => 'u.nom', // Tri par défaut sur le nom
                'defaultSortDirection' => 'asc',
            ]
        );

        return $this->render('utilisateur/gestion.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }



    #[Route('/utilisateurs/new', name: 'utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur, ['is_new' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            if (!is_string($plainPassword)) {
                throw new \LogicException('plainPassword devrait être une chaîne, reçu : ' . gettype($plainPassword));
            }
            $hashedPassword = $passwordHasher->hashPassword($utilisateur, $plainPassword);
            $utilisateur->setPassword($hashedPassword);
            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('utilisateurs_gestion');
        }

        return $this->render('utilisateur/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/utilisateurs/{id}/edit', name: 'utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(UtilisateurType::class, $utilisateur, ['is_new' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                if (!is_string($plainPassword)) {
                    throw new \LogicException('plainPassword devrait être une chaîne, reçu : ' . gettype($plainPassword));
                }
                $hashedPassword = $passwordHasher->hashPassword($utilisateur, $plainPassword);
                $utilisateur->setPassword($hashedPassword);
            }
            $em->flush();

            return $this->redirectToRoute('utilisateurs_gestion');
        }

        return $this->render('utilisateur/edit.html.twig', [
            'form' => $form->createView(),
            'utilisateur' => $utilisateur,
        ]);
    }

	#[Route('/utilisateurs/{id}', name: 'utilisateur_supprimer', methods: ['POST'])]
	    public function supprimer(Request $request, Utilisateur $utilisateur, EntityManagerInterface $em): Response
	    {
		if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
		    // Dissocier les ProjetUtilisateur
		    $projetUtilisateurs = $em->getRepository(ProjetUtilisateur::class)->findBy(['utilisateur' => $utilisateur]);
		    foreach ($projetUtilisateurs as $pu) {
		        $pu->setUtilisateur(null); // Met utilisateur_id à NULL
		        $em->persist($pu);
		    }

		    $em->remove($utilisateur);
		    $em->flush();

		    $this->addFlash('success', 'Utilisateur supprimé avec succès. Les projets associés sont désormais orphelins.');
		} else {
		    $this->addFlash('danger', 'Erreur de sécurité lors de la suppression.');
		}

		return $this->redirectToRoute('utilisateurs_gestion');
	    }	
	
	#[Route('/utilisateur/{id}', name: 'utilisateur_show', methods: ['GET'])]
	public function show(Utilisateur $utilisateur): Response
	{
	    $this->denyAccessUnlessGranted('ROLE_ADMIN');

	    return $this->render('utilisateur/show.html.twig', [
		'utilisateur' => $utilisateur,
		'projetsProprietaire' => $utilisateur->getProjetsProprietaire(),
		'projetsConcepteur' => $utilisateur->getProjetsConcepteur(),
		'projetsLecteur' => $utilisateur->getProjetsLecteur(),
	    ]);
	}

}
