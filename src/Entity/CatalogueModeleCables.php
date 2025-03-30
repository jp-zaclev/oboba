<?php
// src/Entity/CatalogueModeleCables.php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "catalogue_modele_cables")]
class CatalogueModeleCables
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private ?string $nom = null;

    #[ORM\Column(type: "integer")]
    private ?int $nbConducteurs = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2, nullable: false)]
    private float $prixUnitaire = 0.00;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $type = null;

    #[ORM\OneToMany(targetEntity: CatalogueConducteur::class, mappedBy: "catalogueModeleCables", cascade: ["persist", "remove"])]
    private Collection $catalogueConducteurs;

    public function __construct()
    {
        $this->catalogueConducteurs = new ArrayCollection();
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
            $catalogueConducteur->setCatalogueModeleCables($this);
            $catalogueConducteur->setCatalogueProjetCables(null);
        }
        return $this;
    }

    public function removeCatalogueConducteur(CatalogueConducteur $catalogueConducteur): self
    {
        if ($this->catalogueConducteurs->removeElement($catalogueConducteur)) {
            if ($catalogueConducteur->getCatalogueModeleCables() === $this) {
                $catalogueConducteur->setCatalogueModeleCables(null);
            }
        }
        return $this;
    }
}
