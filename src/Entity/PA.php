<?php

namespace App\Entity;

use App\Repository\PARepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PARepository::class)]
class PA
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Thematic $thematic = null;

    #[ORM\ManyToOne(inversedBy: 'pas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Materiality $materiality = null;

    /**
     * @var Collection<int, Stake>
     */
    #[ORM\OneToMany(targetEntity: Stake::class, mappedBy: 'pa', orphanRemoval: true)]
    private Collection $stakes;

    /**
     * @var Collection<int, TIR>
     */
    #[ORM\OneToMany(targetEntity: TIR::class, mappedBy: 'pa', orphanRemoval: true)]
    private Collection $tirs;

    public function __construct()
    {
        $this->stakes = new ArrayCollection();
        $this->tirs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getThematic(): ?Thematic
    {
        return $this->thematic;
    }

    public function setThematic(?Thematic $thematic): static
    {
        $this->thematic = $thematic;

        return $this;
    }

    public function getMateriality(): ?Materiality
    {
        return $this->materiality;
    }

    public function setMateriality(?Materiality $materiality): static
    {
        $this->materiality = $materiality;

        return $this;
    }

    /**
     * @return Collection<int, Stake>
     */
    public function getStakes(): Collection
    {
        return $this->stakes;
    }

    public function addStake(Stake $stake): static
    {
        if (!$this->stakes->contains($stake)) {
            $this->stakes->add($stake);
            $stake->setPa($this);
        }

        return $this;
    }

    public function removeStake(Stake $stake): static
    {
        if ($this->stakes->removeElement($stake)) {
            // set the owning side to null (unless already changed)
            if ($stake->getPa() === $this) {
                $stake->setPa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TIR>
     */
    public function getTirs(): Collection
    {
        return $this->tirs;
    }

    public function addTir(TIR $tir): static
    {
        if (!$this->tirs->contains($tir)) {
            $this->tirs->add($tir);
            $tir->setPa($this);
        }

        return $this;
    }

    public function removeTir(TIR $tir): static
    {
        if ($this->tirs->removeElement($tir)) {
            // set the owning side to null (unless already changed)
            if ($tir->getPa() === $this) {
                $tir->setPa(null);
            }
        }

        return $this;
    }    
}
