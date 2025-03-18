<?php
// src/Entity/Connecteur.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="connecteur")
 * @ORM\Entity
 */
class Connecteur
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
     * @ORM\ManyToOne(targetEntity="Projet", inversedBy="connecteurs")
     * @ORM\JoinColumn(name="id_projet", referencedColumnName="id")
     */
    private $projet;

    /**
     * @ORM\ManyToOne(targetEntity="CatalogueProjetConnecteurs")
     * @ORM\JoinColumn(name="id_catalogue_projet_connecteur", referencedColumnName="id", nullable=true)
     */
    private $catalogueProjetConnecteurs;

    // Getters et Setters
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

    public function getCatalogueProjetConnecteurs(): ?CatalogueProjetConnecteurs
    {
        return $this->catalogueProjetConnecteurs;
    }

    public function setCatalogueProjetConnecteurs(?CatalogueProjetConnecteurs $catalogueProjetConnecteurs): self
    {
        $this->catalogueProjetConnecteurs = $catalogueProjetConnecteurs;
        return $this;
    }
}
