<?php

namespace App\Entity;

use App\Repository\EvidenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvidenceRepository::class)]
class Evidence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'evidences')]
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
