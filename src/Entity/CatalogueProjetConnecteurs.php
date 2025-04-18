<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'catalogue_projet_connecteurs')]
class CatalogueProjetConnecteurs
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Projet::class, inversedBy: 'catalogueProjetConnecteurs')]
    #[ORM\JoinColumn(name: 'id_projet', referencedColumnName: 'id', nullable: false)]
    private ?Projet $projet = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $nom;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $nombreContacts;

    #[ORM\Column(type: 'string', length: 50, nullable: false)]
    private string $type;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private float $prixUnitaire = 0.00;

    #[ORM\OneToMany(targetEntity: CatalogueContact::class, mappedBy: 'catalogueProjetConnecteur', cascade: ['persist', 'remove'])]
    private Collection $catalogueContacts;

    public function __construct()
    {
        $this->catalogueContacts = new ArrayCollection();
    }

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
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

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getNombreContacts(): int
    {
        return $this->nombreContacts;
    }

    public function setNombreContacts(int $nombreContacts): self
    {
        $this->nombreContacts = $nombreContacts;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
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

    /**
     * @return Collection<int, CatalogueContact>
     */
    public function getCatalogueContacts(): Collection
    {
        return $this->catalogueContacts;
    }

    public function addCatalogueContact(CatalogueContact $catalogueContact): self
    {
        if (!$this->catalogueContacts->contains($catalogueContact)) {
            $this->catalogueContacts[] = $catalogueContact;
            $catalogueContact->setCatalogueProjetConnecteur($this);
        }
        return $this;
    }

    public function removeCatalogueContact(CatalogueContact $catalogueContact): self
    {
        if ($this->catalogueContacts->removeElement($catalogueContact)) {
            if ($catalogueContact->getCatalogueProjetConnecteur() === $this) {
                $catalogueContact->setCatalogueProjetConnecteur(null);
            }
        }
        return $this;
    }
}
