<?php

namespace App\Entity;

use App\Repository\InvoicePrestationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoicePrestationRepository::class)]
class InvoicePrestation
{   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invoicePresations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Invoice $invoice = null;

    #[ORM\ManyToOne(inversedBy: 'invoicePresations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prestation $prestation = null;

    #[ORM\Column(length: 255)]
    private ?string $prestationName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): static
    {
        $this->invoice = $invoice;

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

    public function getPrestationName(): ?string
    {
        return $this->prestationName;
    }

    public function setPrestationName(string $prestationName): static
    {
        $this->prestationName = $prestationName;

        return $this;
    }
}
