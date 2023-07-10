<?php

namespace App\Entity;

use App\Entity\Traits\BlameableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\InvoicePrestationRepository;
use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    use TimestampableTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invoice')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

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

    public function getTotal(InvoicePrestationRepository $invoicePrestationRepository)
    {
        $total = 0 ;
        $invoicePrestations = $invoicePrestationRepository->findBy(['invoice' => $this->getId()]);
        foreach($invoicePrestations as $invoicePrestation){
            $prestation = $invoicePrestation->getPrestation();
            $totalPrestation = 0;
            foreach($prestation->getPrestationProducts() as $prestationProduct){
                $totalPrestation += ((($prestationProduct->getProduct()->getTotalTVA() / 100) * $prestationProduct->getProduct()->getTotalHT()) + $prestationProduct->getProduct()->getTotalHT()) * $prestationProduct->getQuantity();
                         
            }
            $totalPrestation += $prestation->getWorkforce();
            $total += $totalPrestation;
        }
        return $total;
    }
}
