<?php

namespace App\Entity;

use App\Enum\ActionPlanStatusEnum;
use App\Repository\ActionPlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActionPlanRepository::class)]
class ActionPlan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $priority = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $progress = 0;

    #[ORM\Column(type: Types::STRING, length: 20, enumType: ActionPlanStatusEnum::class)]
    private ActionPlanStatusEnum $status = ActionPlanStatusEnum::TODO;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, TIR>
     */
    #[ORM\ManyToMany(targetEntity: TIR::class, inversedBy: 'actionPlans')]
    #[ORM\JoinTable(name: 'action_plan_tir')]
    private Collection $tirs;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->tirs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getProgress(): ?int
    {
        return $this->progress;
    }

    public function setProgress(int $progress): static
    {
        $this->progress = max(0, min(100, $progress));

        return $this;
    }

    public function getStatus(): ActionPlanStatusEnum
    {
        return $this->status;
    }

    public function setStatus(ActionPlanStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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
        }

        return $this;
    }

    public function removeTir(TIR $tir): static
    {
        $this->tirs->removeElement($tir);

        return $this;
    }
}
