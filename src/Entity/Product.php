<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Entity\Traits\BlameableTrait;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\PrestationProduct;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[Vich\Uploadable]

class Product
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le nom du Produit est obligatoire')]
    #[Assert\Type(type: 'string', message: 'Le nom du Produit doit être une chaîne de caractères')]
    private ?string $title = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'La quantité est obligatoire')]
    #[Assert\Type(type: 'integer', message: 'La quantité doit être un nombre entier')]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[Assert\NotBlank(message: 'Le Total HT est obligatoire')]
    #[Assert\Type(type: 'float', message: 'Le Total HT doit être un nombre décimal')]
    private ?float $totalHt = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[Assert\NotBlank(message: 'Le Total TVA est obligatoire')]
    #[Assert\Type(type: 'float', message: 'Le Total TVA doit être un nombre décimal')]
    private ?float $totalTva = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Type(type: 'string', message: 'La description doit être une chaîne de caractères')]
    private ?string $description = null;

    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'productImageName')]
    #[Assert\File(
        maxSize: '1024k',
        mimeTypes: ['image/jpg', 'image/jpeg', 'image/png'],
        mimeTypesMessage: 'Veuillez télécharger un fichier jpg, jpeg ou png valide',
    )]    
    private ?File $productImageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $productImageName = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: PrestationProduct::class)]
    private Collection $prestationProducts;

    public function __construct()
    {
        $this->prestationProducts = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTotalHT(): ?float
    {
        return $this->totalHt;
    }

    public function setTotalHT(float $totalHt): static
    {
        $this->totalHt = $totalHt;
        return $this;
    }

    public function getTotalTVA(): ?float
    {
        return $this->totalTva;
    }

    public function setTotalTVA(float $totalTva): static
    {
        $this->totalTva = $totalTva;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getProductImageFile(): ?File
    {
        return $this->productImageFile;
    }

    /**
     * @param File|null $productImageFile
     * @return Product
     */
    public function setProductImageFile(?File $productImageFile): Product
    {
        $this->productImageFile = $productImageFile;

        if (null !== $productImageFile) {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getProductImageName(): ?string
    {
        return $this->productImageName;
    }

    /**
     * @param string|null $productImageName
     * @return Product
     */
    public function setProductImageName(?string $productImageName): Product
    {
        $this->productImageName = $productImageName;
        return $this;
    }

    /**
     * @return Collection|PresationProduct[]
     */
    public function getprestationProducts(): Collection
    {
        return $this->prestationProducts;
    }

    public function addPresationProduct(PresationProduct $presationProduct): self
    {
        if (!$this->prestationProducts->contains($presationProduct)) {
            $this->prestationProducts[] = $presationProduct;
            $presationProduct->setProduct($this);
        }

        return $this;
    }

    public function removePresationProduct(PresationProduct $presationProduct): self
    {
        if ($this->prestationProducts->removeElement($presationProduct)) {
            // set the owning side to null (unless already changed)
            if ($presationProduct->getProduct() === $this) {
                $presationProduct->setProduct(null);
            }
        }

        return $this;
    }
}
