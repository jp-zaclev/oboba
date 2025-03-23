<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'conducteur')]
class Conducteur
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Cable::class, inversedBy: 'conducteurs')]
    #[ORM\JoinColumn(name: 'id_cable', referencedColumnName: 'id', nullable: false)]
    private ?Cable $cable = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $attribut = null;

    #[ORM\ManyToOne(targetEntity: Borne::class)]
    #[ORM\JoinColumn(name: 'id_borne_source', referencedColumnName: 'id', nullable: true)]
    private ?Borne $borneSource = null;

    #[ORM\ManyToOne(targetEntity: Borne::class)]
    #[ORM\JoinColumn(name: 'id_borne_destination', referencedColumnName: 'id', nullable: true)]
    private ?Borne $borneDestination = null;

    #[ORM\ManyToOne(targetEntity: Contact::class)]
    #[ORM\JoinColumn(name: 'id_contact_source', referencedColumnName: 'id', nullable: true)]
    private ?Contact $contactSource = null;

    #[ORM\ManyToOne(targetEntity: Contact::class)]
    #[ORM\JoinColumn(name: 'id_contact_destination', referencedColumnName: 'id', nullable: true)]
    private ?Contact $contactDestination = null;

    #[ORM\ManyToOne(targetEntity: WireSignal::class)]
    #[ORM\JoinColumn(name: 'id_wire_signal', referencedColumnName: 'id', nullable: true)]
    private ?WireSignal $wireSignal = null;

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCable(): ?Cable
    {
        return $this->cable;
    }

    public function setCable(Cable $cable): self
    {
        $this->cable = $cable;
        return $this;
    }

    public function getAttribut(): ?string
    {
        return $this->attribut;
    }

    public function setAttribut(string $attribut): self
    {
        $this->attribut = $attribut;
        return $this;
    }

    public function getBorneSource(): ?Borne
    {
        return $this->borneSource;
    }

    public function setBorneSource(?Borne $borneSource): self
    {
        $this->borneSource = $borneSource;
        return $this;
    }

    public function getBorneDestination(): ?Borne
    {
        return $this->borneDestination;
    }

    public function setBorneDestination(?Borne $borneDestination): self
    {
        $this->borneDestination = $borneDestination;
        return $this;
    }

    public function getContactSource(): ?Contact
    {
        return $this->contactSource;
    }

    public function setContactSource(?Contact $contactSource): self
    {
        $this->contactSource = $contactSource;
        return $this;
    }

    public function getContactDestination(): ?Contact
    {
        return $this->contactDestination;
    }

    public function setContactDestination(?Contact $contactDestination): self
    {
        $this->contactDestination = $contactDestination;
        return $this;
    }

    public function getWireSignal(): ?WireSignal
    {
        return $this->wireSignal;
    }

    public function setWireSignal(?WireSignal $wireSignal): self
    {
        $this->wireSignal = $wireSignal;
        return $this;
    }
}
