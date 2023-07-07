<?php

namespace App\Entity;

use App\Entity\Traits\BlameableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\EstimatePrestationRepository;
use App\Repository\EstimateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
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

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $validityDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Invoice $invoice = null;

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

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

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

    public function getTotal(EstimatePrestationRepository $estimatePrestationRepository)
    {
        $total = 0 ;
        $estimatePrestations = $estimatePrestationRepository->findBy(['estimate' => $this->getId()]);
        foreach($estimatePrestations as $estimatePrestation){
            $prestation = $estimatePrestation->getPrestation();
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
