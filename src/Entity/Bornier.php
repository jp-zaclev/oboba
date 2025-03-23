<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Projet;
use App\Entity\CatalogueProjetBorniers;
use App\Entity\Borne;

#[ORM\Entity]
#[ORM\Table(name: 'bornier')]
#[ORM\UniqueConstraint(name: 'nom_projet_unique', columns: ['nom', 'id_projet'])]
class Bornier
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $nom;

    #[ORM\ManyToOne(targetEntity: CatalogueProjetBorniers::class)]
    #[ORM\JoinColumn(name: 'id_catalogue_projet_bornier', referencedColumnName: 'id', nullable: false)]
    private ?CatalogueProjetBorniers $catalogueProjetBornier = null;

    #[ORM\ManyToOne(targetEntity: Projet::class, inversedBy: 'borniers')]
    #[ORM\JoinColumn(name: 'id_projet', referencedColumnName: 'id', nullable: false)]
    private ?Projet $projet = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $localisation;

    #[ORM\OneToMany(targetEntity: Borne::class, mappedBy: 'bornier', cascade: ['persist', 'remove'])]
    private Collection $bornes;

    public function __construct()
    {
        $this->bornes = new ArrayCollection();
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

    public function getCatalogueProjetBornier(): ?CatalogueProjetBorniers
    {
        return $this->catalogueProjetBornier;
    }

    public function setCatalogueProjetBornier(CatalogueProjetBorniers $catalogue): self
    {
        $this->catalogueProjetBornier = $catalogue;
        return $this;
    }

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(Projet $projet): self
    {
        $this->projet = $projet;
        return $this;
    }

    public function getLocalisation(): string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;
        return $this;
    }

    public function getBornes(): Collection
    {
        return $this->bornes;
    }

    public function addBorne(Borne $borne): self
    {
        if (!$this->bornes->contains($borne)) {
            $this->bornes[] = $borne;
            $borne->setBornier($this);
        }
        return $this;
    }

    public function removeBorne(Borne $borne): self
    {
        if ($this->bornes->removeElement($borne)) {
            if ($borne->getBornier() === $this) {
                $borne->setBornier(null);
            }
        }
        return $this;
    }
}
