<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bornier", uniqueConstraints={@ORM\UniqueConstraint(name="nom_projet_unique", columns={"nom", "id_projet"})})
 * @ORM\Entity
 */
class Bornier
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
     * @ORM\ManyToOne(targetEntity="CatalogueProjetBorniers")
     * @ORM\JoinColumn(name="id_catalogue_projet_bornier", referencedColumnName="id", nullable=false)
     */
    private $catalogueProjetBornier;

    /**
     * @ORM\ManyToOne(targetEntity="Projet")
     * @ORM\JoinColumn(name="id_projet", referencedColumnName="id", nullable=false)
     */
    private $projet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $localisation;

    /**
     * @ORM\OneToMany(targetEntity="Borne", mappedBy="bornier")
     */
    private $bornes;

    public function __construct()
    {
        $this->bornes = new ArrayCollection();
    }

    // Getters et Setters
    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getCatalogueProjetBornier(): ?CatalogueProjetBorniers { return $this->catalogueProjetBornier; }
    public function setCatalogueProjetBornier(CatalogueProjetBorniers $catalogue): self { $this->catalogueProjetBornier = $catalogue; return $this; }
    public function getProjet(): ?Projet { return $this->projet; }
    public function setProjet(Projet $projet): self { $this->projet = $projet; return $this; }
    public function getLocalisation(): ?string { return $this->localisation; }
    public function setLocalisation(string $localisation): self { $this->localisation = $localisation; return $this; }
    public function getBornes(): Collection { return $this->bornes; }
    public function addBorne(Borne $borne): self { if (!$this->bornes->contains($borne)) { $this->bornes[] = $borne; $borne->setBornier($this); } return $this; }
    public function removeBorne(Borne $borne): self { if ($this->bornes->removeElement($borne)) { if ($borne->getBornier() === $this) { $borne->setBornier(null); } } return $this; }
}
