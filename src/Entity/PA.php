<?php

namespace App\Entity;

use App\Repository\PARepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PARepository::class)]
#[UniqueEntity(fields: ['code'], message: 'Ce code est déjà utilisé.')]
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
     * @var Collection<int, Evidence>
     */
    #[ORM\OneToMany(targetEntity: Evidence::class, mappedBy: 'pa', orphanRemoval: true)]
    private Collection $evidences;

    /**
     * @var Collection<int, TIR>
     */
    #[ORM\OneToMany(targetEntity: TIR::class, mappedBy: 'pa', orphanRemoval: true)]
    private Collection $tirs;

    #[ORM\Column(length: 10, unique: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $summary = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->evidences = new ArrayCollection();
        $this->tirs = new ArrayCollection();
    }

    public function getScore(): ?float
    {
        $total = 0;
        $count = 0;
        foreach ($this->tirs as $tir) {
            if (!is_null($tir->getMaturity()->getValue())) {
                $total += $tir->getMaturity()->getValue();
                ++$count;
            }
        }

        return ($count > 0) ? ($total / $count) : null;
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
     * @return Collection<int, Evidence>
     */
    public function getEvidences(): Collection
    {
        return $this->evidences;
    }

    public function addEvidence(Evidence $evidence): static
    {
        if (!$this->evidences->contains($evidence)) {
            $this->evidences->add($evidence);
            $evidence->setPa($this);
        }

        return $this;
    }

    public function removeEvidence(Evidence $evidence): static
    {
        if ($this->evidences->removeElement($evidence)) {
            // set the owning side to null (unless already changed)
            if ($evidence->getPa() === $this) {
                $evidence->setPa(null);
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

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): static
    {
        $this->summary = $summary;

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
}
