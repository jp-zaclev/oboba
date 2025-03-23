<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'wire_signal')]
class WireSignal
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false)]
    private string $type;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $details = null;

    #[ORM\ManyToOne(targetEntity: Projet::class, inversedBy: 'wireSignals')]
    #[ORM\JoinColumn(name: 'id_projet', referencedColumnName: 'id', nullable: false)]
    private ?Projet $projet = null;

    // Getters et Setters
    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getType(): string { return $this->type; }
    public function setType(string $type): self {
        if (!in_array($type, ['analogique', 'digital'])) {
            throw new \InvalidArgumentException("Type invalide : $type");
        }
        $this->type = $type;
        return $this;
    }
    public function getDetails(): ?string { return $this->details; }
    public function setDetails(?string $details): self { $this->details = $details; return $this; }
    public function getProjet(): ?Projet { return $this->projet; }
    public function setProjet(Projet $projet): self { $this->projet = $projet; return $this; }
}
