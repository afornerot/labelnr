<?php

namespace App\Entity;

use App\Repository\MaturityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaturityRepository::class)]
class Maturity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, TIR>
     */
    #[ORM\OneToMany(targetEntity: TIR::class, mappedBy: 'maturity', orphanRemoval: true)]
    private Collection $tirs;

    public function __construct()
    {
        $this->tirs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $tir->setMaturity($this);
        }

        return $this;
    }

    public function removeTir(TIR $tir): static
    {
        if ($this->tirs->removeElement($tir)) {
            // set the owning side to null (unless already changed)
            if ($tir->getMaturity() === $this) {
                $tir->setMaturity(null);
            }
        }

        return $this;
    }
}
