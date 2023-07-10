<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Entity\Traits\BlameableTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2,minMessage:'Le nom doit comporter au moins 2 caractères')]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÿ -]+$/u',
        message: 'La valeur doit être une chaîne de caractères valide pour un prénom ou un nom de famille'
    )]  
    private ?string $lastname = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2,minMessage:'Le prénom doit comporter au moins 2 caractères')]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÿ -]+$/u',
        message: 'La valeur doit être une chaîne de caractères valide pour un prénom ou un nom de famille'
    )]  
    private ?string $firstname = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\Regex(pattern : '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W)[a-zA-Z\d\W]{8,}$/',
    message: '8 caractères requis avec au moins une majuscule, minuscule, un chiffre et un caractère spécial')]
    private ?string $plainPassword = null;

    #[ORM\Column]
    private ?bool $isValidated = false;

    #[ORM\Column]
    private ?string $validationToken = null;

    #[ORM\Column]
    #[Assert\Unique]
    private array $roles = [];

    #[ORM\Column (length: 25, nullable: true)]
    #[Assert\Regex('/^\+?[0-9]+$/')]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $theme = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $language = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_CUSTOMER
        $roles[] = 'ROLE_CUSTOMER';

        return array_unique($roles);
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }
    
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;

        if (null !== $plainPassword) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

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

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;

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

    public function getIsValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $isValidated): self
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    public function getValidationToken(): ?string
    {
        return $this->validationToken;
    }

    public function setValidationToken(string $validationToken): self
    {
        $this->validationToken = $validationToken;

        return $this;
    }


    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'lastname'=>$this->lastname,
            'firstname'=>$this->firstname,
            'email' => $this->email,
            'password' => $this->password,
            'validationToken' => $this->validationToken,
            'isValidated' => $this->isValidated,
            'roles' => $this->roles,
        ];
    }
    public function __unserialize(array $serialized)
    {
        $this->id = $serialized['id'];
        $this->email = $serialized['email'];
        $this->password = $serialized['password'];
        $this->lastname = $serialized['lastname'];
        $this->firstname = $serialized['firstname'];
        // $this->address = $serialized['address'];
        // $this->phone = $serialized['phone'];
        $this->validationToken = $serialized['validationToken'];
        $this->isValidated = $serialized['isValidated'];
        $this->roles = $serialized['roles'];

        return $this;
    }
}
