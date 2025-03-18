<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="projet_utilisateur")
 * @ORM\Entity
 */
class ProjetUtilisateur
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Projet", inversedBy="projetUtilisateurs")
     * @ORM\JoinColumn(name="projet_id", referencedColumnName="id", nullable=false)
     */
    private $projet;

    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="projetUtilisateurs")
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id", nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="enum", columnDefinition="ENUM('proprietaire', 'concepteur', 'lecteur')", nullable=false)
     */
    private $role;

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(Projet $projet): self
    {
        $this->projet = $projet;
        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        if (!in_array($role, ['proprietaire', 'concepteur', 'lecteur'])) {
            throw new \InvalidArgumentException("Rôle invalide : $role");
        }
        $this->role = $role;
        return $this;
    }
}
