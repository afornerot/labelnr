<?php

namespace App\Entity;

use App\Repository\TIRRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TIRRepository::class)]
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
}
