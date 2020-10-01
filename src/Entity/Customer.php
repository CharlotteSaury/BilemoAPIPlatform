<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CustomerRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * 
 * @ApiResource(
 *         attributes={
 *             "pagination_items_per_page"=10,
 *             "security"="is_granted('ROLE_USER')"
 *         },
 *         collectionOperations={
 *          "get"={
 *              "security"="is_granted('MANAGE', object)"
 *          }, 
 *          "post"
 *      },
 *      itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"Customer:read", "Customer:item:get"}}
 *          }, 
 *          "put"={
 *              "security"="is_granted('MANAGE', object)"
 *          }, 
 *          "delete"={
 *              "security"="is_granted('MANAGE', object)"
 *          }
 *      },
 *      normalizationContext={"groups"={"Customer:read"}},
 *      denormalizationContext={"groups"={"Customer:write"}},
 * )
 * 
 * @ApiFilter(SearchFilter::class, properties={"email": "partial"})
 * 
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @UniqueEntity(fields={"email"}, message="This customer already exists")
 * 
 */
class Customer
{
    const ATTRIBUTES = ['email', 'firstname', 'lastname'];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"Customer:read", "client:item:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank
     * @Assert\Email
     * 
     * @Groups({"Customer:read","Customer:write", "client:item:get"})
     * 
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min="2",
     *      max="30"
     * )
     * 
     * @Groups({"Customer:read","Customer:write", "client:item:get"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min="2",
     *      max="30"
     * )
     * 
     * @Groups({"Customer:read","Customer:write", "client:item:get"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Groups({"Customer:read", "client:item:get"})
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity=Client::class, mappedBy="customers")
     * 
     * @Groups({"Customer:item:get"})
     */
    private $clients;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if ($this->clients == null) {
            $this->clients = new ArrayCollection();
        }
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->addCustomer($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->contains($client)) {
            $this->clients->removeElement($client);
            $client->removeCustomer($this);
        }

        return $this;
    }
}
