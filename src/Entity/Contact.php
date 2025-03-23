<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'contact')]
class Contact
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Connecteur::class, inversedBy: 'contacts')]
    #[ORM\JoinColumn(name: 'id_connecteur', referencedColumnName: 'id', nullable: false)]
    private ?Connecteur $connecteur = null;

    #[ORM\Column(type: 'string', length: 50, nullable: false)]
    private string $identifiant;

    #[ORM\Column(type: 'string', length: 20, nullable: false)]
    private string $type;

    public function __construct()
    {
        $this->identifiant = '';
        $this->type = 'emission'; // Valeur par défaut
    }

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConnecteur(): ?Connecteur
    {
        return $this->connecteur;
    }

    public function setConnecteur(Connecteur $connecteur): self
    {
        $this->connecteur = $connecteur;
        return $this;
    }

    public function getIdentifiant(): string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(string $identifiant): self
    {
        $this->identifiant = $identifiant;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        if (!in_array($type, ['emission', 'reception', 'emission_reception'])) {
            throw new \InvalidArgumentException("Type invalide : $type");
        }
        $this->type = $type;
        return $this;
    }
}
