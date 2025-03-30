<?php
// src/Entity/CatalogueConducteur.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity]
#[ORM\Table(name: "catalogue_conducteur")]
class CatalogueConducteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $attribut = null;

    #[ORM\ManyToOne(targetEntity: CatalogueModeleCables::class, inversedBy: "catalogueConducteurs")]
    #[ORM\JoinColumn(nullable: true)]
    private ?CatalogueModeleCables $catalogueModeleCables = null;

    #[ORM\ManyToOne(targetEntity: CatalogueProjetCables::class, inversedBy: "catalogueConducteurs")]
    #[ORM\JoinColumn(nullable: true)]
    private ?CatalogueProjetCables $catalogueProjetCables = null;

    /**
     * Validation pour garantir qu’un CatalogueConducteur est associé à un seul catalogue (modèle OU projet).
     */
    #[Assert\Callback]
    public function validateSingleCatalogue(ExecutionContextInterface $context): void
    {
        // Ne peut pas être associé aux deux en même temps
        if ($this->catalogueModeleCables !== null && $this->catalogueProjetCables !== null) {
            $context->buildViolation('Un conducteur ne peut être associé qu’à un seul catalogue (modèle ou projet).')
                ->atPath('catalogueModeleCables')
                ->addViolation();
        }
        // Doit être associé à au moins un catalogue
        if ($this->catalogueModeleCables === null && $this->catalogueProjetCables === null) {
            $context->buildViolation('Un conducteur doit être associé à un catalogue (modèle ou projet).')
                ->atPath('catalogueModeleCables')
                ->addViolation();
        }
    }

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

    public function getCatalogueModeleCables(): ?CatalogueModeleCables
    {
        return $this->catalogueModeleCables;
    }

    public function setCatalogueModeleCables(?CatalogueModeleCables $catalogueModeleCables): self
    {
        $this->catalogueModeleCables = $catalogueModeleCables;
        return $this;
    }

    public function getCatalogueProjetCables(): ?CatalogueProjetCables
    {
        return $this->catalogueProjetCables;
    }

    public function setCatalogueProjetCables(?CatalogueProjetCables $catalogueProjetCables): self
    {
        $this->catalogueProjetCables = $catalogueProjetCables;
        return $this;
    }
}
