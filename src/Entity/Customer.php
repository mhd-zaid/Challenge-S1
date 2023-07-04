<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[UniqueEntity('email')]
#[ORM\Table(name: '`customer`')]
class Customer implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column(length: 255, nullable : true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2,minMessage:'Le nom doit comporter au moins 2 caractères')]
    private ?string $lastname = null;
    
    #[ORM\Column(length: 255, nullable : true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2,minMessage:'Le prénom doit comporter au moins 2 caractères')]
    private ?string $firstname = null;

    #[ORM\Column(length: 180, unique: true, nullable : true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable : true)]
    private ?string $password = null;

    #[Assert\NotBlank]
    #[Assert\Regex('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W)[a-zA-Z\d\W]{8,}$/')]
    private ?string $plainPassword = null;

    #[ORM\Column(length: 255, nullable : true)]
    private ?string $address = null;
    
    #[ORM\Column(length: 20, nullable : true)]
    #[Assert\Length(max: 20)]
    private ?string $phone = null;

    #[ORM\Column]
    private ?bool $isValidated = false;

    #[ORM\Column(nullable : true)]
    private ?string $validationToken = null;

    #[ORM\Column]
    #[Assert\Unique]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Estimate::class, orphanRemoval: true)]
    private Collection $estimates;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Invoice::class)]
    private Collection $invoices;

    public function __construct()
    {
        $this->estimates = new ArrayCollection();
        $this->invoices = new ArrayCollection();
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

    public function setPlainPassword(?string $plainPassword): Customer
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

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
            'address' => $this->address,
            'phone' => $this->phone,
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
        $this->address = $serialized['address'];
        $this->phone = $serialized['phone'];
        $this->validationToken = $serialized['validationToken'];
        $this->isValidated = $serialized['isValidated'];
        $this->roles = $serialized['roles'];

        return $this;
    }

    /**
     * @return Collection<int, Estimate>
     */
    public function getEstimates(): Collection
    {
        return $this->estimates;
    }

    public function addEstimate(Estimate $estimate): static
    {
        if (!$this->estimates->contains($estimate)) {
            $this->estimates->add($estimate);
            $estimate->setClient($this);
        }

        return $this;
    }

    public function removeEstimate(Estimate $estimate): static
    {
        if ($this->estimates->removeElement($estimate)) {
            // set the owning side to null (unless already changed)
            if ($estimate->getClient() === $this) {
                $estimate->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invoice>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): static
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setClient($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): static
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getClient() === $this) {
                $invoice->setClient(null);
            }
        }

        return $this;
    }
}
