<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ProjetUtilisateur;
use App\Entity\Cable;
use App\Entity\Connecteur;
use App\Entity\CatalogueProjetCables;
use App\Entity\CatalogueProjetConnecteurs;
use App\Entity\CatalogueProjetBorniers; // Ajout
use App\Entity\Equipement;
use App\Entity\Bornier;
use App\Entity\WireSignal;
use App\Repository\ProjetRepository;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
#[ORM\Table(name: 'projet')]
class Projet
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $nom;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $dateHeureCreation;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $dateHeureDerniereModification;

    #[ORM\OneToMany(targetEntity: ProjetUtilisateur::class, mappedBy: 'projet', cascade: ['persist', 'remove'])]
    private Collection $projetUtilisateurs;

    #[ORM\OneToMany(targetEntity: Cable::class, mappedBy: 'projet', cascade: ['persist', 'remove'])]
    private Collection $cables;

    #[ORM\OneToMany(targetEntity: Connecteur::class, mappedBy: 'projet', cascade: ['persist', 'remove'])]
    private Collection $connecteurs;

    #[ORM\OneToMany(targetEntity: CatalogueProjetCables::class, mappedBy: 'projet', cascade: ['persist', 'remove'])]
    private Collection $catalogueProjetCables;

    #[ORM\OneToMany(targetEntity: CatalogueProjetConnecteurs::class, mappedBy: 'projet', cascade: ['persist', 'remove'])]
    private Collection $catalogueProjetConnecteurs;

    #[ORM\OneToMany(targetEntity: CatalogueProjetBorniers::class, mappedBy: 'projet', cascade: ['persist', 'remove'])]
    private Collection $catalogueProjetBorniers;

    #[ORM\OneToMany(targetEntity: Equipement::class, mappedBy: 'projet', cascade: ['persist', 'remove'])]
    private Collection $equipements;

    #[ORM\OneToMany(targetEntity: Bornier::class, mappedBy: 'projet', cascade: ['persist', 'remove'])]
    private Collection $borniers;

    #[ORM\OneToMany(targetEntity: WireSignal::class, mappedBy: 'projet', cascade: ['persist', 'remove'])]
    private Collection $wireSignals;

    public function __construct()
    {
        $this->projetUtilisateurs = new ArrayCollection();
        $this->cables = new ArrayCollection();
        $this->connecteurs = new ArrayCollection();
        $this->catalogueProjetCables = new ArrayCollection();
        $this->catalogueProjetConnecteurs = new ArrayCollection();
        $this->catalogueProjetBorniers = new ArrayCollection();
        $this->equipements = new ArrayCollection();
        $this->borniers = new ArrayCollection();
        $this->wireSignals = new ArrayCollection();
        $this->dateHeureCreation = new \DateTime();
        $this->dateHeureDerniereModification = new \DateTime();
    }

    // Getters et Setters
    public function getId(): ?int { return $this->id; }

    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }

    public function getDateHeureCreation(): \DateTimeInterface { return $this->dateHeureCreation; }
    public function setDateHeureCreation(\DateTimeInterface $date): self { $this->dateHeureCreation = $date; return $this; }

    public function getDateHeureDerniereModification(): \DateTimeInterface { return $this->dateHeureDerniereModification; }
    public function setDateHeureDerniereModification(\DateTimeInterface $date): self { $this->dateHeureDerniereModification = $date; return $this; }

    public function getProjetUtilisateurs(): Collection
    {
        return $this->projetUtilisateurs;
    }

    public function addProjetUtilisateur(ProjetUtilisateur $pu): self
    {
        if (!$this->projetUtilisateurs->contains($pu)) {
            $this->projetUtilisateurs[] = $pu;
            $pu->setProjet($this);
        }
        return $this;
    }

    public function removeProjetUtilisateur(ProjetUtilisateur $pu): self
    {
        if ($this->projetUtilisateurs->removeElement($pu)) {
            if ($pu->getProjet() === $this) {
                $pu->setProjet(null);
            }
        }
        return $this;
    }

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

    public function getCatalogueProjetCables(): Collection
    {
        return $this->catalogueProjetCables;
    }

    public function addCatalogueProjetCable(CatalogueProjetCables $catalogue): self
    {
        if (!$this->catalogueProjetCables->contains($catalogue)) {
            $this->catalogueProjetCables[] = $catalogue;
            $catalogue->setProjet($this);
        }
        return $this;
    }

    public function removeCatalogueProjetCable(CatalogueProjetCables $catalogue): self
    {
        if ($this->catalogueProjetCables->removeElement($catalogue)) {
            if ($catalogue->getProjet() === $this) {
                $catalogue->setProjet(null);
            }
        }
        return $this;
    }

    public function getCatalogueProjetConnecteurs(): Collection
    {
        return $this->catalogueProjetConnecteurs;
    }

    public function addCatalogueProjetConnecteur(CatalogueProjetConnecteurs $catalogue): self
    {
        if (!$this->catalogueProjetConnecteurs->contains($catalogue)) {
            $this->catalogueProjetConnecteurs[] = $catalogue;
            $catalogue->setProjet($this);
        }
        return $this;
    }

    public function removeCatalogueProjetConnecteur(CatalogueProjetConnecteurs $catalogue): self
    {
        if ($this->catalogueProjetConnecteurs->removeElement($catalogue)) {
            if ($catalogue->getProjet() === $this) {
                $catalogue->setProjet(null);
            }
        }
        return $this;
    }

    public function getCatalogueProjetBorniers(): Collection
    {
        return $this->catalogueProjetBorniers;
    }

    public function addCatalogueProjetBornier(CatalogueProjetBorniers $catalogue): self
    {
        if (!$this->catalogueProjetBorniers->contains($catalogue)) {
            $this->catalogueProjetBorniers[] = $catalogue;
            $catalogue->setProjet($this);
        }
        return $this;
    }

    public function removeCatalogueProjetBornier(CatalogueProjetBorniers $catalogue): self
    {
        if ($this->catalogueProjetBorniers->removeElement($catalogue)) {
            if ($catalogue->getProjet() === $this) {
                $catalogue->setProjet(null);
            }
        }
        return $this;
    }

    public function getEquipements(): Collection
    {
        return $this->equipements;
    }

    public function addEquipement(Equipement $equipement): self
    {
        if (!$this->equipements->contains($equipement)) {
            $this->equipements[] = $equipement;
            $equipement->setProjet($this);
        }
        return $this;
    }

    public function removeEquipement(Equipement $equipement): self
    {
        if ($this->equipements->removeElement($equipement)) {
            if ($equipement->getProjet() === $this) {
                $equipement->setProjet(null);
            }
        }
        return $this;
    }

    public function getBorniers(): Collection
    {
        return $this->borniers;
    }

    public function addBornier(Bornier $bornier): self
    {
        if (!$this->borniers->contains($bornier)) {
            $this->borniers[] = $bornier;
            $bornier->setProjet($this);
        }
        return $this;
    }

    public function removeBornier(Bornier $bornier): self
    {
        if ($this->borniers->removeElement($bornier)) {
            if ($bornier->getProjet() === $this) {
                $bornier->setProjet(null);
            }
        }
        return $this;
    }

    public function getWireSignals(): Collection
    {
        return $this->wireSignals;
    }

    public function addWireSignal(WireSignal $wireSignal): self
    {
        if (!$this->wireSignals->contains($wireSignal)) {
            $this->wireSignals[] = $wireSignal;
            $wireSignal->setProjet($this);
        }
        return $this;
    }

    public function removeWireSignal(WireSignal $wireSignal): self
    {
        if ($this->wireSignals->removeElement($wireSignal)) {
            if ($wireSignal->getProjet() === $this) {
                $wireSignal->setProjet(null);
            }
        }
        return $this;
    }

    public function getProprietaire(): ?Utilisateur
    {
        foreach ($this->projetUtilisateurs as $pu) {
            if ($pu->getRole() === 'proprietaire') {
                return $pu->getUtilisateur();
            }
        }
        return null;
    }

    public function getProprietaires(): array
    {
        return $this->projetUtilisateurs
            ->filter(fn(ProjetUtilisateur $pu) => $pu->getRole() === 'proprietaire' && $pu->getUtilisateur() !== null)
            ->map(fn(ProjetUtilisateur $pu) => $pu->getUtilisateur())
            ->toArray();
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
