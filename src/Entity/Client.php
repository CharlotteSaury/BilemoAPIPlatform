<?php

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * 
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"client:read", "client:item:get"}},
 *          }, 
 *          "put", 
 *          "delete"
 *      },
 *      normalizationContext={"groups"={"Client:read"}},
 *      denormalizationContext={"groups"={"Client:write"}},
 *      attributes={
 *          "pagination_items_per_page"=10
 *      }
 * )
 * 
 * @ApiFilter(SearchFilter::class, properties={"company": "partial"})
 * 
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @UniqueEntity(fields={"email"}, message="This client already exists")
 * 
 */
class Client implements UserInterface
{
    const ATTRIBUTES = ['email', 'company'];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"Client:read", "client:item:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank
     * @Assert\Email
     * 
     * @Groups({"Client:read", "Client:write", "client:item:get"})
     * 
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min="6",
     *      max="30",
     *      minMessage="Password must contain at least 6 characters",
     *      maxMessage="Password should not contain more than 30 characters"
     * )
     * 
     * @Groups({"Client:write"})
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Groups({"Client:read", "client:item:get"})
     * 
     */
    private $createdAt;

    /**
     * @ORM\Column(type="json")
     * 
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min="2",
     *      max="50",
     *      minMessage="Company name must contain at least 2 characters",
     *      maxMessage="Company name should not contain more than 50 characters"
     * )
     * 
     * @Groups({"Client:read", "Client:write", "client:item:get", "Customer:item:get"})
     * 
     */
    private $company;

    /**
     * @ORM\ManyToMany(targetEntity=Customer::class, inversedBy="clients")
     * 
     * @Groups({"client:item:get"})
     * 
     */
    private $customers;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->customers = new ArrayCollection();
        $this->roles = ['ROLE_USER'];

    }

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

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getRoles(): ?array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->contains($customer)) {
            $this->customers->removeElement($customer);
        }

        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }
}