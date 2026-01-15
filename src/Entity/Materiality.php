<?php

namespace App\Entity;

use App\Repository\MaterialityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MaterialityRepository::class)]
#[UniqueEntity(fields: ['code'], message: 'Ce code est déjà utilisé.')]
class Materiality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, PA>
     */
    #[ORM\OneToMany(targetEntity: PA::class, mappedBy: 'materiality', orphanRemoval: true)]
    private Collection $pas;

    #[ORM\Column(length: 10, unique: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

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
            $pa->setMateriality($this);
        }

        return $this;
    }

    public function removePa(PA $pa): static
    {
        if ($this->pas->removeElement($pa)) {
            // set the owning side to null (unless already changed)
            if ($pa->getMateriality() === $this) {
                $pa->setMateriality(null);
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
}
