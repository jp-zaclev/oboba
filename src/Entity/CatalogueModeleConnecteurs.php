<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CatalogueModeleConnecteursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CatalogueModeleConnecteursRepository::class)]
#[ORM\Table(name: 'catalogue_modele_connecteurs')]
#[ORM\Unique ZConstraint(name: 'UNIQ_NOM', columns: ['nom'])]
class CatalogueModeleConnecteurs
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false, unique: true)]
    private string $nom;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $nombreContacts;

    #[ORM\Column(type: 'string', length: 50, nullable: false)]
    private string $type;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private float $prixUnitaire = 0.00;

    #[ORM\OneToMany(targetEntity: CatalogueContact::class, mappedBy: 'catalogueModeleConnecteurs', cascade: ['persist', 'remove'])]
    private Collection $catalogueContacts;

    public function __construct()
    {
        $this->catalogueContacts = new ArrayCollection();
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

    public function getNombreContacts(): int
    {
        return $this->nombreContacts;
    }

    public function setNombreContacts(int $nombre): self
    {
        $this->nombreContacts = $nombre;
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

    public function setPrixUnitaire(float $prix): self
    {
        $this->prixUnitaire = $prix;
        return $this;
    }

    public function getCatalogueContacts(): Collection
    {
        return $this->catalogueContacts;
    }

    public function addCatalogueContact(CatalogueContact $contact): self
    {
        if (!$this->catalogueContacts->contains($contact)) {
            $this->catalogueContacts->add($contact);
            $contact->setCatalogueModeleConnecteurs($this);
        }
        return $this;
    }

    public function removeCatalogueContact(CatalogueContact $contact): self
    {
        if ($this->catalogueContacts->removeElement($contact)) {
            if ($contact->getCatalogueModeleConnecteurs() === $this) {
                $contact->setCatalogueModeleConnecteurs(null);
            }
        }
        return $this;
    }
}
