<?php

namespace App\Entity;

use App\Repository\EstimateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstimateRepository::class)]
class Estimate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'estimates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $client = null;

    #[ORM\OneToMany(mappedBy: 'estimate', targetEntity: EstimateProduct::class, orphanRemoval: true)]
    private Collection $estimateProducts;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $validity_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Invoice $invoice = null;

    public function __construct()
    {
        $this->estimateProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientId(): ?int
    {
        return $this->client_id;
    }

    public function setClientId(int $client_id): static
    {
        $this->client_id = $client_id;

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

    public function getClient(): ?Customer
    {
        return $this->client;
    }

    public function setClient(?Customer $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, EstimateProduct>
     */
    public function getEstimateProducts(): Collection
    {
        return $this->estimateProducts;
    }

    public function addEstimateProduct(EstimateProduct $estimateProduct): static
    {
        if (!$this->estimateProducts->contains($estimateProduct)) {
            $this->estimateProducts->add($estimateProduct);
            $estimateProduct->setEstimate($this);
        }

        return $this;
    }

    public function removeEstimateProduct(EstimateProduct $estimateProduct): static
    {
        if ($this->estimateProducts->removeElement($estimateProduct)) {
            // set the owning side to null (unless already changed)
            if ($estimateProduct->getEstimate() === $this) {
                $estimateProduct->setEstimate(null);
            }
        }

        return $this;
    }

    public function getValidityDate(): ?\DateTimeInterface
    {
        return $this->validity_date;
    }

    public function setValidityDate(\DateTimeInterface $validity_date): static
    {
        $this->validity_date = $validity_date;

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
