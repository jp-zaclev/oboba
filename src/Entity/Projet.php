<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="projet")
 * @ORM\Entity(repositoryClass="App\Repository\ProjetRepository")
 */
class Projet
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="ProjetUtilisateur", mappedBy="projet")
     */
    private $projetUtilisateurs;

    /**
     * @ORM\OneToMany(targetEntity="Cable", mappedBy="projet")
     */
    private $cables;

   /**
     * @ORM\OneToMany(targetEntity="Connecteur", mappedBy="projet")
     */
    private $connecteurs;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateHeureCreation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDerniereModification;

    public function __construct()
    {
        $this->projetUtilisateurs = new ArrayCollection();
        $this->cables = new ArrayCollection();
        $this->connecteurs = new ArrayCollection();
        $this->dateHeureCreation = new \DateTime();
        $this->dateHeureDerniereModification = new \DateTime();
    }

    // Getters et Setters
    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getProjetUtilisateurs(): Collection { return $this->projetUtilisateurs; }
    public function addProjetUtilisateur(ProjetUtilisateur $pu): self { if (!$this->projetUtilisateurs->contains($pu)) { $this->projetUtilisateurs[] = $pu; $pu->setProjet($this); } return $this; }
    public function removeProjetUtilisateur(ProjetUtilisateur $pu): self { if ($this->projetUtilisateurs->removeElement($pu)) { if ($pu->getProjet() === $this) { $pu->setProjet(null); } } return $this; }
    public function getDateHeureCreation(): ?\DateTimeInterface { return $this->dateHeureCreation; }
    public function setDateHeureCreation(\DateTimeInterface $date): self { $this->dateHeureCreation = $date; return $this; }
    public function getDateHeureDerniereModification(): ?\DateTimeInterface { return $this->dateHeureDerniereModification; }
    public function setDateHeureDerniereModification(\DateTimeInterface $date): self { $this->dateHeureDerniereModification = $date; return $this; }

    /**
     * @return Collection|Cable[]
     */
    public function getCables(): Collection
    {
        return $this->cables;
    }

    public function addCable(Cable $cable): self
    {
        if (!$this->cables->contains($cable)) {
            $this->cables[] = $cable;
            $cable->setProjet($this);
        }
        return $this;
    }

    public function removeCable(Cable $cable): self
    {
        if ($this->cables->removeElement($cable)) {
            if ($cable->getProjet() === $this) {
                $cable->setProjet(null);
            }
        }
        return $this;
    }

   /**
     * @return Collection|Connecteur[]
     */
    public function getConnecteurs(): Collection
    {
        return $this->connecteurs;
    }

    public function addConnecteur(Connecteur $connecteur): self
    {
        if (!$this->connecteurs->contains($connecteur)) {
            $this->connecteurs[] = $connecteur;
            $connecteur->setProjet($this);
        }
        return $this;
    }

    public function removeConnecteur(Connecteur $connecteur): self
    {
        if ($this->connecteurs->removeElement($connecteur)) {
            if ($connecteur->getProjet() === $this) {
                $connecteur->setProjet(null);
            }
        }
        return $this;
    }

    // Méthodes utilitaires
    public function getProprietaire(): ?Utilisateur
    {
        foreach ($this->projetUtilisateurs as $pu) {
            if ($pu->getRole() === 'proprietaire') {
                return $pu->getUtilisateur();
            }
        }
        return null;
    }

    public function getConcepteurs(): Collection
    {
        return $this->projetUtilisateurs->filter(function (ProjetUtilisateur $pu) {
            return $pu->getRole() === 'concepteur';
        })->map(function (ProjetUtilisateur $pu) {
            return $pu->getUtilisateur();
        });
    }

    public function getLecteurs(): Collection
    {
        return $this->projetUtilisateurs->filter(function (ProjetUtilisateur $pu) {
            return $pu->getRole() === 'lecteur';
        })->map(function (ProjetUtilisateur $pu) {
            return $pu->getUtilisateur();
        });
    }
}
