<?php

namespace App\Entity;

use App\Entity\Traits\BlameableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\PrestationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PrestationRepository::class)]
#[Vich\Uploadable]

class Prestation
{
    use TimestampableTrait;
    use BlameableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom de la Prestation est obligatoire')]
    #[Assert\Type(type: 'string', message: 'Le nom de la Prestation doit être une chaîne de caractères')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom de la Catégorie est obligatoire')]
    #[Assert\Type(type: 'string', message: 'Le nom de la Catégorie doit être une chaîne de caractères')]
    private ?string $category = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'L\'estimation de la Durée est obligatoire, elle doit être exprimée en minutes')]
    #[Assert\Type(type: 'integer', message: 'La Durée doit être un entier, elle doit être exprimée en minutes')]
    private ?int $duration = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La main d\'oeuvre est obligatoire')]
    #[Assert\Type(type: 'int', message: 'Le nom du technicien doit être une chaîne de caractères')]
    private ?int $workforce = null;

    #[ORM\OneToMany(mappedBy: 'prestation', targetEntity: PrestationProduct::class, orphanRemoval: true, cascade: ['remove'])]
    private Collection $prestationProducts;

    #[ORM\OneToMany(mappedBy: 'prestation', targetEntity: EstimatePrestation::class, orphanRemoval: true, cascade: ['remove'])]
    private Collection $estimatePrestations;
    
    public function __construct()
    {
        $this->prestationProducts = new ArrayCollection();
        $this->estimatePrestations = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;
        return $this;
    }

    public function getWorkforce(): ?int
    {
        return $this->workforce;
    }

    public function setWorkforce(int $workforce): static
    {
        $this->workforce = $workforce;

        return $this;
    }

    public function getPrestationProducts(): Collection
    {
        return $this->prestationProducts;
    }

    public function addPrestationProduct(PrestationProduct $prestationProduct): static
    {
        if (!$this->prestationProducts->contains($prestationProduct)) {
            $this->prestationProducts[] = $prestationProduct;
            $prestationProduct->setPrestation($this);
        }
        return $this;
    }

    public function removePrestationProduct(PrestationProduct $prestationProduct): static
    {
        if ($this->prestationProducts->removeElement($prestationProduct)) {
            // set the owning side to null (unless already changed)
            if ($prestationProduct->getPrestation() === $this) {
                $prestationProduct->setPrestation(null);
            }
        }
        return $this;
    }

    public function getEstimatePrestations(): Collection
    {
        return $this->estimatePrestations;
    }

    public function addEstimatePrestation(EstimatePrestation $estimatePrestation): static
    {
        if (!$this->estimatePrestations->contains($estimatePrestation)) {
            $this->estimatePrestations[] = $estimatePrestation;
            $estimatePrestation->setPrestation($this);
        }
        return $this;
    }

    public function removeEstimatePrestation(EstimatePrestation $estimatePrestation): static
    {
        if ($this->estimatePrestations->removeElement($estimatePrestation)) {
            // set the owning side to null (unless already changed)
            if ($estimatePrestation->getPrestation() === $this) {
                $estimatePrestation->setPrestation(null);
            }
        }
        return $this;
    }

}
