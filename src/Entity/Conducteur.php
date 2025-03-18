<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="conducteur")
 * @ORM\Entity
 */
class Conducteur
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Cable", inversedBy="conducteurs")
     * @ORM\JoinColumn(name="id_cable", referencedColumnName="id", nullable=false)
     */
    private $cable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $attribut;

    /**
     * @ORM\ManyToOne(targetEntity="Borne")
     * @ORM\JoinColumn(name="id_borne_source", referencedColumnName="id", nullable=true)
     */
    private $borneSource;

    /**
     * @ORM\ManyToOne(targetEntity="Borne")
     * @ORM\JoinColumn(name="id_borne_destination", referencedColumnName="id", nullable=true)
     */
    private $borneDestination;

    /**
     * @ORM\ManyToOne(targetEntity="Contact")
     * @ORM\JoinColumn(name="id_contact_source", referencedColumnName="id", nullable=true)
     */
    private $contactSource;

    /**
     * @ORM\ManyToOne(targetEntity="Contact")
     * @ORM\JoinColumn(name="id_contact_destination", referencedColumnName="id", nullable=true)
     */
    private $contactDestination;

    /**
     * @ORM\ManyToOne(targetEntity="Signal")
     * @ORM\JoinColumn(name="id_signal", referencedColumnName="id", nullable=true)
     */
    private $signal;

    // Getters et Setters
    public function getId(): ?int { return $this->id; }
    public function getCable(): ?Cable { return $this->cable; }
    public function setCable(Cable $cable): self { $this->cable = $cable; return $this; }
    public function getAttribut(): ?string { return $this->attribut; }
    public function setAttribut(string $attribut): self { $this->attribut = $attribut; return $this; }
    public function getBorneSource(): ?Borne { return $this->borneSource; }
    public function setBorneSource(?Borne $borne): self { $this->borneSource = $borne; return $this; }
    public function getBorneDestination(): ?Borne { return $this->borneDestination; }
    public function setBorneDestination(?Borne $borne): self { $this->borneDestination = $borne; return $this; }
    public function getContactSource(): ?Contact { return $this->contactSource; }
    public function setContactSource(?Contact $contact): self { $this->contactSource = $contact; return $this; }
    public function getContactDestination(): ?Contact { return $this->contactDestination; }
    public function setContactDestination(?Contact $contact): self { $this->contactDestination = $contact; return $this; }
    public function getSignal(): ?Signal { return $this->signal; }
    public function setSignal(?Signal $signal): self { $this->signal = $signal; return $this; }
}
