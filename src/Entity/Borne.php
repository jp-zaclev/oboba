<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="borne")
 * @ORM\Entity
 */
class Borne
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Bornier", inversedBy="bornes")
     * @ORM\JoinColumn(name="id_bornier", referencedColumnName="id", nullable=false)
     */
    private $bornier;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $identification;

    // Getters et Setters
    public function getId(): ?int { return $this->id; }
    public function getBornier(): ?Bornier { return $this->bornier; }
    public function setBornier(Bornier $bornier): self { $this->bornier = $bornier; return $this; }
    public function getIdentification(): ?string { return $this->identification; }
    public function setIdentification(string $identification): self { $this->identification = $identification; return $this; }
}
