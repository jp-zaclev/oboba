<?php
// src/Entity/CatalogueProjetCables.php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "catalogue_projet_cables")]
#[ORM\UniqueConstraint(name: "unique_projet_nom", columns: ["id_projet", "nom"])]
class CatalogueProjetCables
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Projet::class, inversedBy: "catalogueProjetCables")]
    #[ORM\JoinColumn(name: "id_projet", referencedColumnName: "id", nullable: false)]
    private ?Projet $projet = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: "integer")]
    private ?int $nbConducteurs = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2, nullable: false)]
    private float $prixUnitaire = 0.00;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $type = null;

    #[ORM\OneToMany(targetEntity: CatalogueConducteur::class, mappedBy: "catalogueProjetCables", cascade: ["persist", "remove"])]
    private Collection $catalogueConducteurs;

    public function __construct()
    {
        $this->catalogueConducteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getNbConducteurs(): ?int
    {
        return $this->nbConducteurs;
    }

    public function setNbConducteurs(int $nbConducteurs): self
    {
        $this->nbConducteurs = $nbConducteurs;
        return $this;
    }

    public function getPrixUnitaire(): float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(float $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Collection|CatalogueConducteur[]
     */
    public function getCatalogueConducteurs(): Collection
    {
        return $this->catalogueConducteurs;
    }

    public function addCatalogueConducteur(CatalogueConducteur $catalogueConducteur): self
    {
        if (!$this->catalogueConducteurs->contains($catalogueConducteur)) {
            $this->catalogueConducteurs[] = $catalogueConducteur;
            $catalogueConducteur->setCatalogueProjetCables($this);
        }
        return $this;
    }

    public function removeCatalogueConducteur(CatalogueConducteur $catalogueConducteur): self
    {
        if ($this->catalogueConducteurs->removeElement($catalogueConducteur)) {
            if ($catalogueConducteur->getCatalogueProjetCables() === $this) {
                $catalogueConducteur->setCatalogueProjetCables(null);
            }
        }
        return $this;
    }
}
