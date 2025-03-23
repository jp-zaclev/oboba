<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Projet;
use App\Entity\Utilisateur;

#[ORM\Entity]
#[ORM\Table(name: 'projet_utilisateur')]
class ProjetUtilisateur
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Projet::class, inversedBy: 'projetUtilisateurs')]
    #[ORM\JoinColumn(name: 'projet_id', referencedColumnName: 'id', nullable: false)]
    private ?Projet $projet = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'projetUtilisateurs')]
    #[ORM\JoinColumn(name: 'utilisateur_id', referencedColumnName: 'id', nullable: true)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false)]
    private string $role;

    public function getId(): ?int { return $this->id; }
    public function getProjet(): ?Projet { return $this->projet; }
    public function setProjet(Projet $projet): self { $this->projet = $projet; return $this; }
    public function getUtilisateur(): ?Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(?Utilisateur $utilisateur): self { $this->utilisateur = $utilisateur; return $this; }
    public function getRole(): string { return $this->role; }
    public function setRole(string $role): self {
        if (!in_array($role, ['proprietaire', 'concepteur', 'lecteur'])) {
            throw new \InvalidArgumentException("Rôle invalide : $role");
        }
        $this->role = $role;
        return $this;
    }
}
