<?php
// src/Entity/Cable.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="cable")
 * @ORM\Entity
 */
class Cable
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
     * @ORM\ManyToOne(targetEntity="Projet", inversedBy="cables")
     * @ORM\JoinColumn(name="id_projet", referencedColumnName="id")
     */
    private $projet;

    /**
     * @ORM\ManyToOne(targetEntity="CatalogueProjetCables")
     * @ORM\JoinColumn(name="id_catalogue_projet_cable", referencedColumnName="id", nullable=true)
     */
    private $catalogueProjetCables;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $longueur;

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
