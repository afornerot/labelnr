<?php

namespace App\Entity;

use App\Repository\StakeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StakeRepository::class)]
class Stake
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'stakes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PA $pa = null;

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
}
