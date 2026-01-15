<?php

namespace App\Entity;

use App\Repository\ThematicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThematicRepository::class)]
class Thematic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, PA>
     */
    #[ORM\OneToMany(targetEntity: PA::class, mappedBy: 'thematic', orphanRemoval: true)]
    private Collection $pas;

    public function __construct()
    {
        $this->pas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, PA>
     */
    public function getPas(): Collection
    {
        return $this->pas;
    }

    public function addPa(PA $pa): static
    {
        if (!$this->pas->contains($pa)) {
            $this->pas->add($pa);
            $pa->setThematic($this);
        }

        return $this;
    }

    public function removePa(PA $pa): static
    {
        if ($this->pas->removeElement($pa)) {
            // set the owning side to null (unless already changed)
            if ($pa->getThematic() === $this) {
                $pa->setThematic(null);
            }
        }

        return $this;
    }


}
