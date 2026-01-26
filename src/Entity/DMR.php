<?php

namespace App\Entity;

use App\Enum\DMRTypeEnum;
use App\Repository\DMRRepository;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\Column(enumType: DMRTypeEnum::class)]
    private ?DMRTypeEnum $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

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

    public function getType(): ?DMRTypeEnum
    {
        return $this->type;
    }

    public function setType(DMRTypeEnum $type): static
    {
        $this->type = $type;

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
