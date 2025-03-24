<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\ProjetUtilisateur;
use App\Entity\Utilisateur;
use App\Entity\CatalogueModeleCables; 
use App\Entity\CatalogueProjetCables; 
use App\Entity\CatalogueModeleConnecteurs; 
use App\Entity\CatalogueProjetConnecteurs; 
use App\Entity\CatalogueModeleBorniers; 
use App\Entity\CatalogueProjetBorniers; 
use App\Form\ProjetType;
use App\Form\ProjetRecrutementType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/projets')]
class ProjetController extends AbstractController
{
    #[Route('', name: 'projet_list', methods: ['GET'])]
    public function list(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $qb = $em->getRepository(Projet::class)->createQueryBuilder('p');

        $projets = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10,
            [
                'defaultSortFieldName' => 'p.nom',
                'defaultSortDirection' => 'asc',
            ]
        );

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
		$proprietaireId = $request->request->get('proprietaire');
		$concepteursIds = $request->request->get('concepteurs', []);
		$lecteursIds = $request->request->get('lecteurs', []);

		if ($proprietaireId) {
		    $proprietaire = $em->getRepository(Utilisateur::class)->find($proprietaireId);
		    if ($proprietaire) {
		        $puProprietaire = new ProjetUtilisateur();
		        $puProprietaire->setProjet($projet)
		                       ->setUtilisateur($proprietaire)
		                       ->setRole('proprietaire');
		        $projet->addProjetUtilisateur($puProprietaire);
		    }
		}

		foreach ($concepteursIds as $concepteurId) {
		    $concepteur = $em->getRepository(Utilisateur::class)->find($concepteurId);
		    if ($concepteur) {
		        $pu = new ProjetUtilisateur();
		        $pu->setProjet($projet)->setUtilisateur($concepteur)->setRole('concepteur');
		        $projet->addProjetUtilisateur($pu);
		    }
		}

		foreach ($lecteursIds as $lecteurId) {
		    $lecteur = $em->getRepository(Utilisateur::class)->find($lecteurId);
		    if ($lecteur) {
		        $pu = new ProjetUtilisateur();
		        $pu->setProjet($projet)->setUtilisateur($lecteur)->setRole('lecteur');
		        $projet->addProjetUtilisateur($pu);
		    }
		}

		$projet->setDateHeureDerniereModification(new \DateTime());
		$this->importCataloguesFromModele($projet, $em); // Appel à la méthode factorisée
		$em->persist($projet);
		$em->flush();

		$this->addFlash('success', 'Projet créé avec succès.');
		return $this->redirectToRoute('projet_list');
	    }

	    $utilisateurs = $em->getRepository(Utilisateur::class)->findAll();

	    return $this->render('projet/new.html.twig', [
		'form' => $form->createView(),
		'utilisateurs' => $utilisateurs,
	    ]);
	}
    #[Route('/{id}/edit', name: 'projet_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Projet $projet, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($projet->getProjetUtilisateurs() as $pu) {
                $em->remove($pu);
            }
            $projet->getProjetUtilisateurs()->clear();

            $proprietaire = $form->get('proprietaire')->getData();
            $puProprietaire = new ProjetUtilisateur();
            $puProprietaire->setProjet($projet)
                           ->setUtilisateur($proprietaire)
                           ->setRole('proprietaire');
            $projet->addProjetUtilisateur($puProprietaire);

            foreach ($form->get('concepteurs')->getData() as $concepteur) {
                $pu = new ProjetUtilisateur();
                $pu->setProjet($projet)->setUtilisateur($concepteur)->setRole('concepteur');
                $projet->addProjetUtilisateur($pu);
            }

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
    public function mesProjets(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator): Response
    {
        $utilisateur = $this->getUser();
        if (!$utilisateur) {
            return $this->redirectToRoute('login');
        }

        $qb = $em->getRepository(Projet::class)->createQueryBuilder('p')
            ->innerJoin('p.projetUtilisateurs', 'pu')
            ->where('pu.utilisateur = :utilisateur')
            ->setParameter('utilisateur', $utilisateur)
            ->addSelect('pu');

        $projets = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10,
            [
                'defaultSortFieldName' => 'p.nom',
                'defaultSortDirection' => 'asc',
            ]
        );

        return $this->render('projet/mes_projets.html.twig', [
            'projets' => $projets,
        ]);
    }

	#[Route('/mes-projets/new', name: 'projet_mes_projets_new', methods: ['GET', 'POST'])]
	public function mesProjetsNew(Request $request, EntityManagerInterface $em): Response
	{
	    $utilisateur = $this->getUser();
	    if (!$utilisateur) {
		return $this->redirectToRoute('login');
	    }

	    $projet = new Projet();
	    $form = $this->createForm(ProjetType::class, $projet);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
		$pu = new ProjetUtilisateur();
		$pu->setProjet($projet)
		   ->setUtilisateur($utilisateur)
		   ->setRole('proprietaire');

		$this->importCataloguesFromModele($projet, $em); // Appel à la méthode factorisée

		$em->persist($projet);
		$em->persist($pu);
		$em->flush();

		$this->addFlash('success', 'Projet créé avec succès.');
		return $this->redirectToRoute('projet_mes_projets');
	    }

	    return $this->render('projet/mes_projets_new.html.twig', [
		'form' => $form->createView(),
	    ]);
	}

    #[Route('/{id}/recrutement', name: 'projet_recrutement', methods: ['GET', 'POST'])]
    public function recrutement(Request $request, Projet $projet, EntityManagerInterface $em): Response
    {
        $utilisateur = $this->getUser();
        if (!$utilisateur) {
            return $this->redirectToRoute('login');
        }

        $projetUtilisateurs = $projet->getProjetUtilisateurs();
        if ($projetUtilisateurs instanceof \Doctrine\ORM\PersistentCollection && !$projetUtilisateurs->isInitialized()) {
            $projetUtilisateurs->initialize();
        }

        $isProprietaire = $projetUtilisateurs->exists(function ($key, $pu) use ($utilisateur) {
            return $pu instanceof ProjetUtilisateur &&
                   $pu->getUtilisateur() &&
                   $pu->getUtilisateur()->getId() === $utilisateur->getId() &&
                   $pu->getRole() === 'proprietaire';
        });

        if (!$this->isGranted('ROLE_ADMIN') && !$isProprietaire) {
            throw $this->createAccessDeniedException('Seul un propriétaire ou un admin peut accéder à cette page.');
        }

        $form = $this->createForm(ProjetRecrutementType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $utilisateurs = $data['utilisateurs'];
            $roles = $data['roles'];

            foreach ($utilisateurs as $index => $recru) {
                if ($recru) {
                    $role = $roles[$index] ?? 'lecteur';
                    $existing = $projetUtilisateurs->filter(fn($pu) => $pu->getUtilisateur() && $pu->getUtilisateur()->getId() === $recru->getId())->first();
                    if (!$existing) {
                        $pu = new ProjetUtilisateur();
                        $pu->setProjet($projet)
                           ->setUtilisateur($recru)
                           ->setRole($role);
                        $em->persist($pu);
                    }
                }
            }

            $em->flush();
            $this->addFlash('success', 'Utilisateurs recrutés avec succès.');
            return $this->redirectToRoute('projet_recrutement', ['id' => $projet->getId()]);
        }

        return $this->render('projet/recrutement.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
            'projetUtilisateurs' => $projetUtilisateurs,
            'isAdmin' => $this->isGranted('ROLE_ADMIN'),
            'isProprietaire' => $isProprietaire,
        ]);
    }

    #[Route('/{id}/revoquer/{projetUtilisateurId}', name: 'projet_revoquer', methods: ['POST'])]
    public function revoquer(Request $request, Projet $projet, int $projetUtilisateurId, EntityManagerInterface $em): Response
    {
        $utilisateur = $this->getUser();
        if (!$utilisateur) {
            return $this->redirectToRoute('login');
        }

        $projetUtilisateur = $em->getRepository(ProjetUtilisateur::class)->find($projetUtilisateurId);
        if (!$projetUtilisateur || $projetUtilisateur->getProjet() !== $projet) {
            throw $this->createNotFoundException('Utilisateur ou projet non trouvé.');
        }

        $isAdmin = $this->isGranted('ROLE_ADMIN');
        $isProprietaire = $projet->getProjetUtilisateurs()->exists(function ($key, $pu) {
            return $pu instanceof ProjetUtilisateur && $pu->getUtilisateur() === $this->getUser() && $pu->getRole() === 'proprietaire';
        });

        if (!$isAdmin && !$isProprietaire) {
            throw $this->createAccessDeniedException('Vous n’avez pas le droit de révoquer.');
        }

        $role = $projetUtilisateur->getRole();
        if ($isProprietaire && !$isAdmin && $role === 'proprietaire') {
            throw $this->createAccessDeniedException('Un propriétaire ne peut pas révoquer un autre propriétaire.');
        }

        if ($this->isCsrfTokenValid('revoquer_' . $projetUtilisateurId, $request->request->get('_token'))) {
            $em->remove($projetUtilisateur);
            $em->flush();
            $this->addFlash('success', 'Droits de l’utilisateur révoqués avec succès.');
        } else {
            $this->addFlash('danger', 'Erreur de sécurité lors de la révocation.');
        }

        return $this->redirectToRoute('projet_recrutement', ['id' => $projet->getId()]);
    }

    #[Route('/{id}/assigner-proprietaire', name: 'projet_assigner_proprietaire', methods: ['GET', 'POST'])]
    public function assignerProprietaire(Request $request, Projet $projet, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createFormBuilder()
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'nom',
                'label' => 'Nouveau propriétaire',
                'placeholder' => 'Sélectionnez un utilisateur',
            ])
            ->add('submit', SubmitType::class, ['label' => 'Assigner'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur = $form->get('utilisateur')->getData();

            if (!$utilisateur) {
                $this->addFlash('error', 'Utilisateur non trouvé.');
                return $this->redirectToRoute('projet_list');
            }

            $orphelins = $em->getRepository(ProjetUtilisateur::class)->findBy([
                'projet' => $projet,
                'utilisateur' => null,
                'role' => 'proprietaire'
            ]);
            foreach ($orphelins as $orphelin) {
                $em->remove($orphelin);
            }

            $pu = new ProjetUtilisateur();
            $pu->setProjet($projet)
               ->setUtilisateur($utilisateur)
               ->setRole('proprietaire');
            $em->persist($pu);

            $em->flush();
            $this->addFlash('success', 'Propriétaire assigné avec succès.');
            return $this->redirectToRoute('projet_list');
        }

        return $this->render('projet/assigner_proprietaire.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'projet_show', methods: ['GET'])]
    public function show(Projet $projet): Response
    {
        $utilisateur = $this->getUser();
        if (!$utilisateur) {
            return $this->redirectToRoute('login');
        }

        $isAuthorized = $projet->getProjetUtilisateurs()->exists(function ($key, $pu) use ($utilisateur) {
            return $pu instanceof ProjetUtilisateur && $pu->getUtilisateur() && $pu->getUtilisateur()->getId() === $utilisateur->getId();
        });

        if (!$isAuthorized) {
            throw $this->createAccessDeniedException('Vous n’avez pas accès à ce projet.');
        }

        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
        ]);
    }
    
	private function importCataloguesFromModele(Projet $projet, EntityManagerInterface $em): void
	{
	    // Importation du catalogue des câbles
	    $modeleCables = $em->getRepository(CatalogueModeleCables::class)->findAll();
	    foreach ($modeleCables as $modele) {
		$projetCable = new CatalogueProjetCables();
		$projetCable->setProjet($projet)
		            ->setNom($modele->getNom())
		            ->setNombreConducteursMax($modele->getNombreConducteursMax())
		            ->setPrixUnitaire($modele->getPrixUnitaire())
		            ->setType($modele->getType());
		$em->persist($projetCable);
		$projet->addCatalogueProjetCable($projetCable);
	    }

	    // Importation du catalogue des connecteurs
	    $modeleConnecteurs = $em->getRepository(CatalogueModeleConnecteurs::class)->findAll();
	    foreach ($modeleConnecteurs as $modele) {
		$projetConnecteur = new CatalogueProjetConnecteurs();
		$projetConnecteur->setProjet($projet)
		                 ->setNom($modele->getNom())
		                 ->setType($modele->getType())
		                 ->setNombreContacts($modele->getNombreContacts())
		                 ->setPrixUnitaire($modele->getPrixUnitaire());
		$em->persist($projetConnecteur);
		$projet->addCatalogueProjetConnecteur($projetConnecteur);
	    }

	    // Importation du catalogue des borniers
	    $modeleBorniers = $em->getRepository(CatalogueModeleBorniers::class)->findAll();
	    foreach ($modeleBorniers as $modele) {
		$projetBornier = new CatalogueProjetBorniers();
		$projetBornier->setProjet($projet)
		              ->setNom($modele->getNom())
		              ->setNombreBornes($modele->getNombreBornes())
		              ->setCaracteristiques($modele->getCaracteristiques())
		              ->setPrixUnitaire($modele->getPrixUnitaire());
		$em->persist($projetBornier);
		$projet->addCatalogueProjetBornier($projetBornier);
	    }
	}

}
