<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="catalogue_projet_borniers")
 * @ORM\Entity
 */
class CatalogueProjetBorniers
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Projet")
     * @ORM\JoinColumn(name="id_projet", referencedColumnName="id", nullable=false)
     */
    private $projet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreBornes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caracteristiques;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $prixUnitaire;

    // Getters et Setters
    public function getId(): ?int { return $this->id; }
    public function getProjet(): ?Projet { return $this->projet; }
    public function setProjet(Projet $projet): self { $this->projet = $projet; return $this; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getNombreBornes(): ?int { return $this->nombreBornes; }
    public function setNombreBornes(int $nombre): self { $this->nombreBornes = $nombre; return $this; }
    public function getCaracteristiques(): ?string { return $this->caracteristiques; }
    public function setCaracteristiques(?string $caracteristiques): self { $this->caracteristiques = $caracteristiques; return $this; }
    public function getPrixUnitaire(): ?string { return $this->prixUnitaire; }
    public function setPrixUnitaire(string $prix): self { $this->prixUnitaire = $prix; return $this; }
}
