<?php

namespace App\Entity;

use App\Repository\TIRRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: TIRRepository::class)]
#[UniqueEntity(fields: ['code'], message: 'Ce code est déjà utilisé.')]
class TIR
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tirs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PA $pa = null;

    #[ORM\ManyToOne(inversedBy: 'tirs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Maturity $maturity = null;

    /**
     * @var Collection<int, DMR>
     */
    #[ORM\OneToMany(targetEntity: DMR::class, mappedBy: 'tir', orphanRemoval: true)]
    private Collection $dmrs;

    #[ORM\Column(length: 10, unique: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $summary = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    public function __construct()
    {
        $this->dmrs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPa(): ?PA
    {
        return $this->pa;
    }

    public function setPa(?PA $pa): static
    {
        $this->pa = $pa;

        return $this;
    }

    public function getMaturity(): ?Maturity
    {
        return $this->maturity;
    }

    public function setMaturity(?Maturity $maturity): static
    {
        $this->maturity = $maturity;

        return $this;
    }

    /**
     * @return Collection<int, DMR>
     */
    public function getDmrs(): Collection
    {
        return $this->dmrs;
    }

    public function addDmr(DMR $dmr): static
    {
        if (!$this->dmrs->contains($dmr)) {
            $this->dmrs->add($dmr);
            $dmr->setTir($this);
        }

        return $this;
    }

    public function removeDmr(DMR $dmr): static
    {
        if ($this->dmrs->removeElement($dmr)) {
            // set the owning side to null (unless already changed)
            if ($dmr->getTir() === $this) {
                $dmr->setTir(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }
}
