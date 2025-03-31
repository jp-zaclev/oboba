<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'catalogue_contact')]
class CatalogueContact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CatalogueModeleConnecteurs::class, inversedBy: 'catalogueContacts')]
    #[ORM\JoinColumn(name: 'catalogue_modele_connecteurs_id', nullable: true, onDelete: 'CASCADE')]
    private ?CatalogueModeleConnecteurs $catalogueModeleConnecteurs = null;

    #[ORM\ManyToOne(targetEntity: CatalogueProjetConnecteurs::class, inversedBy: 'catalogueContacts')]
    #[ORM\JoinColumn(name: 'catalogue_projet_connecteurs_id', nullable: true, onDelete: 'CASCADE')]
    private ?CatalogueProjetConnecteurs $catalogueProjetConnecteur = null;

    #[ORM\Column(type: 'string', length: 50, nullable: false)]
    private string $identifiant;

    #[ORM\Column(type: 'string', length: 20, nullable: false)]
    private string $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatalogueModeleConnecteurs(): ?CatalogueModeleConnecteurs
    {
        return $this->catalogueModeleConnecteurs;
    }

    public function setCatalogueModeleConnecteurs(?CatalogueModeleConnecteurs $catalogueModeleConnecteurs): self
    {
        $this->catalogueModeleConnecteurs = $catalogueModeleConnecteurs;
        return $this;
    }

    public function getCatalogueProjetConnecteur(): ?CatalogueProjetConnecteurs
    {
        return $this->catalogueProjetConnecteur;
    }

    public function setCatalogueProjetConnecteur(?CatalogueProjetConnecteurs $catalogueProjetConnecteur): self
    {
        $this->catalogueProjetConnecteur = $catalogueProjetConnecteur;
        return $this;
    }

    public function getIdentifiant(): string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(string $identifiant): self
    {
        $this->identifiant = $identifiant;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        if (!in_array($type, ['emission', 'reception', 'emission_reception'])) {
            throw new \InvalidArgumentException("Type invalide : $type");
        }
        $this->type = $type;
        return $this;
    }
}
