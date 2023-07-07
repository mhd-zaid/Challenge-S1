<?php

namespace App\Entity;

use App\Repository\EstimatePrestationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstimatePrestationRepository::class)]
class EstimatePrestation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Estimate $estimate = null;

    private ?Prestation $prestation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEstimate(): ?Estimate
    {
        return $this->estimate;
    }

    public function setEstimate(?estimate $estimate): static
    {
        $this->estimate = $estimate;

        return $this;
    }

    public function getPrestation(): ?Prestation
    {
        return $this->prestation;
    }

    public function setPrestation(?Prestation $prestation): static
    {
        $this->prestation = $prestation;

        return $this;
    }
}
