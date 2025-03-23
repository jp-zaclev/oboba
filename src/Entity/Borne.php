<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Bornier;

#[ORM\Entity]
#[ORM\Table(name: 'borne')]
class Borne
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Bornier::class, inversedBy: 'bornes')]
    #[ORM\JoinColumn(name: 'id_bornier', referencedColumnName: 'id', nullable: false)]
    private ?Bornier $bornier = null;

    #[ORM\Column(type: 'string', length: 50, nullable: false)]
    private string $identification;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBornier(): ?Bornier
    {
        return $this->bornier;
    }

    public function setBornier(Bornier $bornier): self
    {
        $this->bornier = $bornier;
        return $this;
    }

    public function getIdentification(): string
    {
        return $this->identification;
    }

    public function setIdentification(string $identification): self
    {
        $this->identification = $identification;
        return $this;
    }
}
