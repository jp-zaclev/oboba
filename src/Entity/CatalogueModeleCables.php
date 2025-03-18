<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="catalogue_modele_cables")
 * @ORM\Entity
 */
class CatalogueModeleCables
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreConducteursMax;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $prixMetre;

    // Getters et Setters
    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getType(): ?string { return $this->type; }
    public function setType(string $type): self { $this->type = $type; return $this; }
    public function getNombreConducteursMax(): ?int { return $this->nombreConducteursMax; }
    public function setNombreConducteursMax(int $nombre): self { $this->nombreConducteursMax = $nombre; return $this; }
    public function getPrixMetre(): ?string { return $this->prixMetre; }
    public function setPrixMetre(string $prix): self { $this->prixMetre = $prix; return $this; }
}
