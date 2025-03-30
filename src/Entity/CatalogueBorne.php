<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'catalogue_borne')]
class CatalogueBorne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $attribut = null;

    #[ORM\ManyToOne(targetEntity: CatalogueModeleBorniers::class, inversedBy: 'catalogueBornes')]
    #[ORM\JoinColumn(name: 'catalogue_modele_borniers_id', nullable: true, onDelete: 'CASCADE')]
    private ?CatalogueModeleBorniers $catalogueModeleBorniers = null;

    #[ORM\ManyToOne(targetEntity: CatalogueProjetBorniers::class, inversedBy: 'catalogueBornes')]
    #[ORM\JoinColumn(name: 'catalogue_projet_borniers_id', nullable: true, onDelete: 'CASCADE')]
    private ?CatalogueProjetBorniers $catalogueProjetBorniers = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttribut(): ?string
    {
        return $this->attribut;
    }

    public function setAttribut(?string $attribut): self
    {
        $this->attribut = $attribut;
        return $this;
    }

    public function getCatalogueModeleBorniers(): ?CatalogueModeleBorniers
    {
        return $this->catalogueModeleBorniers;
    }

    public function setCatalogueModeleBorniers(?CatalogueModeleBorniers $catalogueModeleBorniers): self
    {
        $this->catalogueModeleBorniers = $catalogueModeleBorniers;
        return $this;
    }

    public function getCatalogueProjetBorniers(): ?CatalogueProjetBorniers
    {
        return $this->catalogueProjetBorniers;
    }

    public function setCatalogueProjetBorniers(?CatalogueProjetBorniers $catalogueProjetBorniers): self
    {
        $this->catalogueProjetBorniers = $catalogueProjetBorniers;
        return $this;
    }
}
