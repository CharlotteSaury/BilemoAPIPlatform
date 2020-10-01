<?php

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * 
 * @ApiResource(
 *      attributes={
 *          "security"="is_granted('ROLE_USER')",
 *          "pagination_items_per_page"=10
 *      },
 *      collectionOperations={
 *          "get", 
 *          "post"={
 *              "security"="is_granted('ROLE_ADMIN')"
 *          }
 *      },
 *      itemOperations={
 *          "get", 
 *          "delete"={
 *              "security"="is_granted('ROLE_ADMIN')"
 *          }
 *      },
 *      normalizationContext={"groups"={"Product:read"}},
 *      denormalizationContext={"groups"={"Product:write"}}
 * )
 * @ApiFilter(SearchFilter::class, properties={"name": "partial"})
 * 
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @UniqueEntity(fields={"name"}, message="This product already exists.")
 * 
 * 
 */
class Product
{

    const ATTRIBUTES = ['name', 'description', 'screen', 'das', 'weight', 'length', 'width', 'height', 'wifi', 'video4k', 'bluetooth', 'camera', 'manufacturer'];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"Product:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min="2",
     *      max="50",
     *      minMessage="Product name must contain at least 2 characters",
     *      maxMessage="Product name should not contain more than 50 characters"
     * )
     * 
     * @Groups({"Product:read", "Product:write"})
     * 
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min="6",
     *      max="3000",
     *      minMessage="Product description must contain at least 6 characters",
     *      maxMessage="Product description should not contain more than 3000 characters"
     * )
     * 
     * @Groups({"Product:read", "Product:write"})
     * 
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min="2",
     *      max="50",
     *      minMessage="Manufacturer name must contain at least 2 characters",
     *      maxMessage="Manufacturer name should not contain more than 50 characters"
     * )
     * 
     * @Groups({"Product:read", "Product:write"})
     */
    private $manufacturer;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Groups({"Product:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     * 
     * @Assert\NotBlank
     * @Assert\Positive
     * @Assert\Type(
     *      type="numeric",
     *      message="This value should be a numeric value"
     *      )
     * @Assert\Type(
     *     type="float",
     *     message="This value is not a valid float number"
     * )
     * 
     * @Groups({"Product:read", "Product:write"})
     */
    private $screen;

    /**
     * @ORM\Column(type="float")
     * 
     * @Assert\NotBlank
     * @Assert\Type(
     *      type="numeric",
     *      message="This value should be a numeric value"
     *      )
     * @Assert\Type(
     *     type="float",
     *     message="This value is not a valid float number"
     * )
     * 
     * @Groups({"Product:read", "Product:write"})
     */
    private $das;

    /**
     * @ORM\Column(type="float")
     * 
     * @Assert\NotBlank
     * @Assert\Positive
     * @Assert\Type(
     *      type="numeric",
     *      message="This value should be a numeric value"
     *      )
     * @Assert\Type(
     *     type="float",
     *     message="This value is not a valid float number"
     * )
     * 
     * @Groups({"Product:read", "Product:write"})
     */
    private $weight;

    /**
     * @ORM\Column(type="float")
     * 
     * @Assert\NotBlank
     * @Assert\Positive
     * @Assert\Type(
     *      type="numeric",
     *      message="This value should be a numeric value"
     *      )
     * @Assert\Type(
     *     type="float",
     *     message="This value is not a valid float number"
     * )
     * 
     * @Groups({"Product:read", "Product:write"})
     */
    private $length;

    /**
     * @ORM\Column(type="float")
     * 
     * @Assert\NotBlank
     * @Assert\Positive
     * @Assert\Type(
     *      type="numeric",
     *      message="This value should be a numeric value"
     *      )
     * @Assert\Type(
     *     type="float",
     *     message="This value is not a valid float number"
     * )
     * 
     * @Groups({"Product:read", "Product:write"})
     */
    private $width;

    /**
     * @ORM\Column(type="float")
     * 
     * @Assert\NotBlank
     * @Assert\Positive
     * @Assert\Type(
     *      type="numeric",
     *      message="This value should be a numeric value"
     *      )
     * @Assert\Type(
     *     type="float",
     *     message="This value is not a valid float number"
     * )
     * 
     * @Groups({"Product:read", "Product:write"})
     */
    private $height;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * 
     * @Assert\NotNull
     * @Assert\Type(
     *      type="bool",
     *      message="This value should be a boolean"
     * )
     * 
     * @Groups({"Product:read", "Product:write"})
     */
    private $wifi;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * 
     * @Assert\NotNull
     * @Assert\Type(
     *      type="bool",
     *      message="This value should be a boolean"
     * )
     * 
     * @Groups({"Product:read", "Product:write"})
     */
    private $video4k;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * 
     * @Assert\NotNull
     * @Assert\Type(
     *      type="bool",
     *      message="This value should be a boolean"
     * )
     * 
     * @Groups({"Product:read", "Product:write"})
     */
    private $bluetooth;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * 
     * @Assert\NotNull
     * @Assert\Type(
     *      type="bool",
     *      message="This value should be a boolean"
     * )
     * 
     * @Groups({"Product:read", "Product:write"})
     */
    private $camera;

    /**
     * @ORM\OneToMany(targetEntity=Configuration::class, mappedBy="product", orphanRemoval=true, cascade={"persist"})
     * 
     * @Assert\NotNull
     * @Assert\NotBlank
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "You must specify at least one configuration")
     * @Assert\Valid
     * 
     * @Groups({"Product:read", "Product:write"})
     * 
     */
    private $configurations;

    public function __construct()
    {
        $this->configurations = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getScreen(): ?float
    {
        return $this->screen;
    }

    public function setScreen(float $screen): self
    {
        $this->screen = $screen;

        return $this;
    }

    public function getDas(): ?float
    {
        return $this->das;
    }

    public function setDas(float $das): self
    {
        $this->das = $das;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    public function setLength(float $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(float $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWifi(): ?bool
    {
        return $this->wifi;
    }

    public function setWifi(?bool $wifi): self
    {
        $this->wifi = $wifi;

        return $this;
    }

    public function getVideo4k(): ?bool
    {
        return $this->video4k;
    }

    public function setVideo4k(?bool $video4k): self
    {
        $this->video4k = $video4k;

        return $this;
    }

    public function getBluetooth(): ?bool
    {
        return $this->bluetooth;
    }

    public function setBluetooth(?bool $bluetooth): self
    {
        $this->bluetooth = $bluetooth;

        return $this;
    }

    public function getCamera(): ?bool
    {
        return $this->camera;
    }

    public function setCamera(?bool $camera): self
    {
        $this->camera = $camera;

        return $this;
    }

    /**
     * @return Collection|Configuration[]
     */
    public function getConfigurations(): Collection
    {
        return $this->configurations;
    }

    public function addConfiguration(Configuration $configuration): self
    {
        if (!$this->configurations->contains($configuration)) {
            $this->configurations[] = $configuration;
            $configuration->setProduct($this);
        }

        return $this;
    }

    public function removeConfiguration(Configuration $configuration): self
    {
        if ($this->configurations->contains($configuration)) {
            $this->configurations->removeElement($configuration);
            // set the owning side to null (unless already changed)
            if ($configuration->getProduct() === $this) {
                $configuration->setProduct(null);
            }
        }

        return $this;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(string $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }
}