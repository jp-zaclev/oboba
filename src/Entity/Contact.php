<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="contact")
 * @ORM\Entity
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Connecteur", inversedBy="contacts")
     * @ORM\JoinColumn(name="id_connecteur", referencedColumnName="id", nullable=false)
     */
    private $connecteur;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $identifiant;

    /**
     * @ORM\Column(type="enum", columnDefinition="ENUM('emission', 'reception', 'emission_reception')", nullable=false)
     */
    private $type;

    // Getters et Setters
    public function getId(): ?int { return $this->id; }
    public function getConnecteur(): ?Connecteur { return $this->connecteur; }
    public function setConnecteur(Connecteur $connecteur): self { $this->connecteur = $connecteur; return $this; }
    public function getIdentifiant(): ?string { return $this->identifiant; }
    public function setIdentifiant(string $identifiant): self { $this->identifiant = $identifiant; return $this; }
    public function getType(): ?string { return $this->type; }
    public function setType(string $type): self { $this->type = $type; return $this; }
}
