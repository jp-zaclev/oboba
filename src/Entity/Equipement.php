<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="equipement")
 * @ORM\Entity
 */
class Equipement
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
     * @ORM\Column(type="string", length=50)
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity="Projet")
     * @ORM\JoinColumn(name="id_projet", referencedColumnName="id", nullable=false)
     */
    private $projet;

    /**
     * @ORM\OneToMany(targetEntity="Connecteur", mappedBy="equipement")
     */
    private $connecteurs;

    public function __construct()
    {
        $this->connecteurs = new ArrayCollection();
    }

    // Getters et Setters
    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getReference(): ?string { return $this->reference; }
    public function setReference(string $reference): self { $this->reference = $reference; return $this; }
    public function getProjet(): ?Projet { return $this->projet; }
    public function setProjet(Projet $projet): self { $this->projet = $projet; return $this; }
    public function getConnecteurs(): Collection { return $this->connecteurs; }
    public function addConnecteur(Connecteur $connecteur): self { if (!$this->connecteurs->contains($connecteur)) { $this->connecteurs[] = $connecteur; $connecteur->setEquipement($this); } return $this; }
    public function removeConnecteur(Connecteur $connecteur): self { if ($this->connecteurs->removeElement($connecteur)) { if ($connecteur->getEquipement() === $this) { $connecteur->setEquipement(null); } } return $this; }
}
