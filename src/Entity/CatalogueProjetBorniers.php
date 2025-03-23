<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Projet;

#[ORM\Entity]
#[ORM\Table(name: 'catalogue_projet_borniers')]
class CatalogueProjetBorniers
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Projet::class, inversedBy: 'catalogueProjetBorniers')]
    #[ORM\JoinColumn(name: 'id_projet', referencedColumnName: 'id', nullable: false)]
    private ?Projet $projet = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $nom;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $nombreBornes;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $caracteristiques = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private float $prixUnitaire;

    public function getId(): ?int { return $this->id; }
    public function getProjet(): ?Projet { return $this->projet; }
    public function setProjet(Projet $projet): self { $this->projet = $projet; return $this; }
    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getNombreBornes(): int { return $this->nombreBornes; }
    public function setNombreBornes(int $nombre): self { $this->nombreBornes = $nombre; return $this; }
    public function getCaracteristiques(): ?string { return $this->caracteristiques; }
    public function setCaracteristiques(?string $caracteristiques): self { $this->caracteristiques = $caracteristiques; return $this; }
    public function getPrixUnitaire(): float { return $this->prixUnitaire; }
    public function setPrixUnitaire(float $prix): self { $this->prixUnitaire = $prix; return $this; }
}
