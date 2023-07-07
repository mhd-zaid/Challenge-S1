<?php

namespace App\Entity;

use App\Entity\Traits\BlameableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\EstimateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstimateRepository::class)]
class Estimate
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'estimates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\OneToMany(mappedBy: 'estimate', targetEntity: EstimatePrestation::class, orphanRemoval: true, cascade: ['remove'])]
    private Collection $estimatePrestations;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $validityDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Invoice $invoice = null;

    public function __construct()
    {
        $this->estimatePrestations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getestimatePrestations(): Collection
    {
        return $this->estimatePrestations;
    }

    public function addEstimatePresation(EstimatePrestation $estimatePrestation): static
    {
        if (!$this->estimatePrestations->contains($estimatePrestation)) {
            $this->estimatePrestations->add($estimatePrestation);
            $estimatePrestation->setEstimate($this);
        }

        return $this;
    }

    public function removeEstimatePresation(EstimatePrestation $estimatePresation): static
    {
        if ($this->estimatePrestations->removeElement($estimatePresation)) {
            // set the owning side to null (unless already changed)
            if ($estimatePresation->getEstimate() === $this) {
                $estimatePresation->setEstimate(null);
            }
        }

        return $this;
    }

    public function getValidityDate(): ?\DateTimeInterface
    {
        return $this->validityDate;
    }

    public function setValidityDate(\DateTimeInterface $validityDate): static
    {
        $this->validityDate = $validityDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(Invoice $invoice): static
    {
        $this->invoice = $invoice;

        return $this;
    }
}
