<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'connecteur')]
class Connecteur
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(targetEntity: Projet::class, inversedBy: 'connecteurs')]
    #[ORM\JoinColumn(name: 'id_projet', referencedColumnName: 'id')]
    private ?Projet $projet = null;

    #[ORM\ManyToOne(targetEntity: CatalogueProjetConnecteurs::class)]
    #[ORM\JoinColumn(name: 'id_catalogue_projet_connecteur', referencedColumnName: 'id', nullable: true)]
    private ?CatalogueProjetConnecteurs $catalogueProjetConnecteurs = null;

    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'connecteur', cascade: ['persist', 'remove'])]
    private Collection $contacts;

    #[ORM\ManyToOne(targetEntity: Equipement::class, inversedBy: 'connecteurs')]
    #[ORM\JoinColumn(name: 'id_equipement', referencedColumnName: 'id', nullable: true)]
    private ?Equipement $equipement = null;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): self
    {
        $this->projet = $projet;
        return $this;
    }

    public function getCatalogueProjetConnecteurs(): ?CatalogueProjetConnecteurs
    {
        return $this->catalogueProjetConnecteurs;
    }

    public function setCatalogueProjetConnecteurs(?CatalogueProjetConnecteurs $catalogueProjetConnecteurs): self
    {
        $this->catalogueProjetConnecteurs = $catalogueProjetConnecteurs;
        return $this;
    }

    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setConnecteur($this);
        }
        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            if ($contact->getConnecteur() === $this) {
                $contact->setConnecteur(null);
            }
        }
        return $this;
    }

    public function getEquipement(): ?Equipement
    {
        return $this->equipement;
    }

    public function setEquipement(?Equipement $equipement): self
    {
        $this->equipement = $equipement;
        return $this;
    }
}
