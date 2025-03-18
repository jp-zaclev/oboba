<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="ProjetUtilisateur", mappedBy="utilisateur")
     */
    private $projetUtilisateurs;

    public function __construct()
    {
        $this->projetUtilisateurs = new ArrayCollection();
    }

    // Getters et Setters pour UserInterface
    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function getPassword(): ?string { return $this->password; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }
    public function getRoles(): array { return array_unique(array_merge(['ROLE_USER'], $this->roles)); }
    public function setRoles(array $roles): self { $this->roles = $roles; return $this; }
    public function getSalt() { return null; }
    public function getUsername(): string { return $this->email; }
    public function getUserIdentifier(): string {return (string) $this->email; }
    public function eraseCredentials() {}
    public function getProjetUtilisateurs(): Collection { return $this->projetUtilisateurs; }
    public function addProjetUtilisateur(ProjetUtilisateur $pu): self { if (!$this->projetUtilisateurs->contains($pu)) { $this->projetUtilisateurs[] = $pu; $pu->setUtilisateur($this); } return $this; }
    public function removeProjetUtilisateur(ProjetUtilisateur $pu): self { if ($this->projetUtilisateurs->removeElement($pu)) { if ($pu->getUtilisateur() === $this) { $pu->setUtilisateur(null); } } return $this; }
}
