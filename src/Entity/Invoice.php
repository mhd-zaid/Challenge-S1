<?php

namespace App\Entity;

use App\Entity\Traits\BlameableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    use TimestampableTrait;
    use BlameableTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invoice')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $client = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: InvoicePrestation::class, orphanRemoval: true, cascade: ['remove'])]
    private Collection $invoicePrestations;

    public function __construct()
    {
        $this->invoicePrestations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, InvoiceProduct>
     */
    public function getInvoiceProducts(): Collection
    {
        return $this->invoicePrestations;
    }

    public function addInvoiceProduct(InvoicePrestation $invoicePrestation): static
    {
        if (!$this->invoicePrestations->contains($invoicePrestation)) {
            $this->invoicePrestations->add($invoicePrestation);
            $invoicePrestation->setInvoice($this);
        }

        return $this;
    }

    public function removeInvoiceProduct(InvoicePrestation $invoicePrestations): static
    {
        if ($this->invoicePrestations->removeElement($invoicePrestations)) {
            // set the owning side to null (unless already changed)
            if ($invoicePrestations->getInvoice() === $this) {
                $invoicePrestations->setInvoice(null);
            }
        }

        return $this;
    }
}
