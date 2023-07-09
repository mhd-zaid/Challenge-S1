<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[Vich\Uploadable]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom de la compagnie est obligatoire')]
    #[Assert\Type(type: 'string', message: 'Le nom de la compagnie doit être une chaîne de caractères')]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'La date de création de l\'entreprise')]
    private ?\DateTimeInterface $dateOfCreation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le prénom du Propriétaire du site est obligatoire')]
    #[Assert\Type(type: 'string', message: 'Le prénom du Propriétaire du site doit être une chaîne de caractères')]
    private ?string $ownerFirstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom du Propriétaire du site est obligatoire')]
    #[Assert\Type(type: 'string', message: 'Le nom du Propriétaire du site doit être une chaîne de caractères')]
    private ?string $ownerLastName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'L\'Adresse de la compagnie est obligatoire')]
    #[Assert\Type(type: 'string', message: 'L\'Adresse de la compagnie doit être une chaîne de caractères')]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le numéro de téléphone de la compagnie est obligatoire')]
    #[Assert\Type(type: 'string', message: 'Le numéro de téléphone de la compagnie doit être une chaîne de caractères')]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'L\'email de la compagnie est obligatoire')]
    #[Assert\Email(message: 'L\'email de la compagnie doit être une adresse email valide')]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le numéro de Siret est obligatoire')]
    #[Assert\Type(type: 'string', message: 'Le numéro de Siret doit être une chaîne de caractères')]
    private ?string $siret = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le numéro de TVA est obligatoire')]
    #[Assert\Type(type: 'string', message: 'Le numéro de TVA doit être une chaîne de caractères')]
    private ?string $tva = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La langue du site est obligatoire')]
    #[Assert\Type(type: 'string', message: 'La langue doit être une chaîne de caractères')]
    private ?string $language = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La Devise est obligatoire')]
    #[Assert\Type(type: 'string', message: 'La Devise doit être une chaîne de caractères')]
    private ?string $currency = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom du Theme est obligatoire')]
    #[Assert\Type(type: 'string', message: 'Le nom du Theme doit être une chaîne de caractères')]
    private ?string $theme = null;

    #[Vich\UploadableField(mapping: 'companies', fileNameProperty: 'companyImageName')]
    #[Assert\File(
        maxSize: '1024k',
        mimeTypes: ['image/jpg', 'image/jpeg', 'image/png'],
        mimeTypesMessage: 'Veuillez télécharger un fichier jpg, jpeg ou png valide',
    )]
    private ?File $companyImageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $companyImageName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La Description de la compagnie est obligatoire')]
    #[Assert\Type(type: 'string', message: 'La Description de la compagnie doit être une chaîne de caractères')]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
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

    public function getDateOfCreation(): ?\DateTimeInterface
    {
        return $this->dateOfCreation;
    }

    public function setDateOfCreation(\DateTimeInterface $dateOfCreation): static
    {
        $this->dateOfCreation = $dateOfCreation;

        return $this;
    }

    public function getOwnerFirstName(): ?string
    {
        return $this->ownerFirstName;
    }

    public function setOwnerFirstName(string $ownerFirstName): static
    {
        $this->ownerFirstName = $ownerFirstName;

        return $this;
    }

    public function getOwnerLastName(): ?string
    {
        return $this->ownerLastName;
    }

    public function setOwnerLastName(string $ownerLastName): static
    {
        $this->ownerLastName = $ownerLastName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getTva(): ?string
    {
        return $this->tva;
    }

    public function setTva(string $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getCompanyImageFile(): ?File
    {
        return $this->companyImageFile;
    }

    /**
     * @param File|null $companyImageFile
     * @return Company
     */
    public function setCompanyImageFile(?File $companyImageFile): Company
    {
        $this->companyImageFile = $companyImageFile;

        if (null !== $companyImageFile) {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyImageName(): ?string
    {
        return $this->companyImageName;
    }

    /**
     * @param string|null $companyImageName
     * @return Company
     */
    public function setCompanyImageName(?string $companyImageName): Company
    {
        $this->companyImageName = $companyImageName;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
