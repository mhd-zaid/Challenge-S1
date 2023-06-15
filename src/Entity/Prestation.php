<?php

namespace App\Entity;

use App\Entity\Traits\BlameableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\PrestationRepository;
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

    #[Vich\UploadableField(mapping: 'prestations', fileNameProperty: 'prestationImageName')]
    #[Assert\File(
        maxSize: '1024k',
        mimeTypes: ['image/jpg', 'image/jpeg', 'image/png'],
        mimeTypesMessage: 'Veuillez télécharger un fichier jpg, jpeg ou png valide',
    )]
    private ?File $prestationImageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $prestationImageName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom du Technicien est obligatoire')]
    #[Assert\Type(type: 'string', message: 'Le nom du technicien doit être une chaîne de caractères')]
    private ?string $technician = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[Assert\NotBlank(message: 'Le Total HT est obligatoire')]
    #[Assert\Type(type: 'float', message: 'Le Total HT doit être un nombre décimal')]
    private ?float $total_ht = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[Assert\NotBlank(message: 'Le Total TVA est obligatoire')]
    #[Assert\Type(type: 'float', message: 'Le Total TVA doit être un nombre décimal')]
    private ?float $total_tva = null;

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

    /**
     * @return File|null
     */
    public function getPrestationImageFile(): ?File
    {
        return $this->prestationImageFile;
    }

    /**
     * @param File|null $prestationImageFile
     * @return Prestation
     */
    public function setPrestationImageFile(?File $prestationImageFile): Prestation
    {
        $this->prestationImageFile = $prestationImageFile;
        if (null !== $prestationImageFile) {
            $this->updatedAt = new \DateTime();
        }
        return $this;
    }

    public function getPrestationImageName(): ?string
    {
        return $this->prestationImageName;
    }

    public function setPrestationImageName(string $prestationImageName): static
    {
        $this->prestationImageName = $prestationImageName;
        return $this;
    }

    public function getTechnician(): ?string
    {
        return $this->technician;
    }

    public function setTechnician(string $technician): static
    {
        $this->technician = $technician;
        return $this;
    }

    public function getTotalHT(): ?float
    {
        return $this->total_ht;
    }

    public function setTotalHT(float $total_ht): static
    {
        $this->total_ht = $total_ht;
        return $this;
    }

    public function getTotalTVA(): ?float
    {
        return $this->total_tva;
    }

    public function setTotalTVA(float $total_tva): static
    {
        $this->total_tva = $total_tva;
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
