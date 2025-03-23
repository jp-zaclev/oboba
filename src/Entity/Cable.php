<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'cable')]
class Cable
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(targetEntity: Projet::class, inversedBy: 'cables')]
    #[ORM\JoinColumn(name: 'id_projet', referencedColumnName: 'id')]
    private ?Projet $projet = null;

    #[ORM\ManyToOne(targetEntity: CatalogueProjetCables::class)]
    #[ORM\JoinColumn(name: 'id_catalogue_projet_cable', referencedColumnName: 'id', nullable: true)]
    private ?CatalogueProjetCables $catalogueProjetCables = null;

    #[ORM\OneToMany(targetEntity: Conducteur::class, mappedBy: 'cable', cascade: ['persist', 'remove'])]
    private Collection $conducteurs;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $longueur = null;

    public function __construct()
    {
        $this->conducteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): self
    {
        $this->projet = $projet;
        return $this;
    }

    public function getCatalogueProjetCables(): ?CatalogueProjetCables
    {
        return $this->catalogueProjetCables;
    }

    public function setCatalogueProjetCables(?CatalogueProjetCables $catalogueProjetCables): self
    {
        $this->catalogueProjetCables = $catalogueProjetCables;
        return $this;
    }

    public function getConducteurs(): Collection
    {
        return $this->conducteurs;
    }

    public function addConducteur(Conducteur $conducteur): self
    {
        if (!$this->conducteurs->contains($conducteur)) {
            $this->conducteurs[] = $conducteur;
            $conducteur->setCable($this);
        }
        return $this;
    }

    public function removeConducteur(Conducteur $conducteur): self
    {
        if ($this->conducteurs->removeElement($conducteur)) {
            if ($conducteur->getCable() === $this) {
                $conducteur->setCable(null);
            }
        }
        return $this;
    }

    public function getLongueur(): ?int
    {
        return $this->longueur;
    }

    public function setLongueur(?int $longueur): self
    {
        $this->longueur = $longueur;
        return $this;
    }
}
