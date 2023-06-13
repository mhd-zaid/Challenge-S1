<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Entity\Traits\BlameableTrait;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

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
    private ?string $title = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'productImageName')]
    private ?File $productImageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $productImageName = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

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

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
    public function unserialize(array $serialized)
    {
        $this->id = $serialized['id'];
        $this->email = $serialized['email'];
        $this->password = $serialized['password'];
        return $this;
    }
}
