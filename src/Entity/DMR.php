<?php

namespace App\Entity;

use App\Repository\DMRRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DMRRepository::class)]
class DMR
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'dmrs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TIR $tir = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTir(): ?TIR
    {
        return $this->tir;
    }

    public function setTir(?TIR $tir): static
    {
        $this->tir = $tir;

        return $this;
    }
}
