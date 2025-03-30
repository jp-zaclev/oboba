<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'catalogue_projet_borniers')]
class CatalogueProjetBorniers
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $nom;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $nombreBornes;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $caracteristiques = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private float $prixUnitaire = 0.00;

    #[ORM\ManyToOne(targetEntity: Projet::class, inversedBy: 'catalogueProjetBorniers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Projet $projet = null;

    #[ORM\OneToMany(targetEntity: CatalogueBorne::class, mappedBy: 'catalogueProjetBorniers', cascade: ['persist', 'remove'])]
    private Collection $catalogueBornes;

    public function __construct()
    {
        $this->catalogueBornes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getNombreBornes(): int
    {
        return $this->nombreBornes;
    }

    public function setNombreBornes(int $nombre): self
    {
        $this->nombreBornes = $nombre;
        return $this;
    }

    public function getCaracteristiques(): ?string
    {
        return $this->caracteristiques;
    }

    public function setCaracteristiques(?string $caracteristiques): self
    {
        $this->caracteristiques = $caracteristiques;
        return $this;
    }

    public function getPrixUnitaire(): float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(float $prix): self
    {
        $this->prixUnitaire = $prix;
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

    /**
     * @return Collection|CatalogueBorne[]
     */
    public function getCatalogueBornes(): Collection
    {
        return $this->catalogueBornes;
    }

    public function addCatalogueBorne(CatalogueBorne $borne): self
    {
        if (!$this->catalogueBornes->contains($borne)) {
            $this->catalogueBornes[] = $borne;
            $borne->setCatalogueProjetBorniers($this);
        }
        return $this;
    }

    public function removeCatalogueBorne(CatalogueBorne $borne): self
    {
        if ($this->catalogueBornes->removeElement($borne)) {
            if ($borne->getCatalogueProjetBorniers() === $this) {
                $borne->setCatalogueProjetBorniers(null);
            }
        }
        return $this;
    }
}
