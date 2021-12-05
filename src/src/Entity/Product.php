<?php
namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    public const MY_GROUP = "product";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Ignore]
    private int $id;

    #[ORM\Column(type: 'uuid', unique:TRUE)]
    #[Groups([ProductProperty::MY_GROUP, ProductCategory::MY_GROUP])]
    private Uuid $uuid;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups([self::MY_GROUP])]
    private string $name;

    #[ORM\Column(type: Types::BIGINT, options:["default" => 0])]
    #[Groups([self::MY_GROUP])]
    private int $price = 0;

    #[ORM\Column(type: Types::BIGINT)]
    #[Groups([self::MY_GROUP])]
    private int $createdAt = 0;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductCategory::class, orphanRemoval: true)]
    #[Groups([self::MY_GROUP])]
    private Collection $productCategories;

    #[ORM\OneToOne(mappedBy: 'product', targetEntity: ProductProperty::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups([self::MY_GROUP])]
    #[MaxDepth(1)]
    private ?ProductProperty $productProperty;

    public function __construct()
    {
        $this->uuid = Uuid::v6();
        $this->productCategories = new ArrayCollection();

        if ($this->createdAt === 0) {
            $this->createdAt = time();
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getIdentifier(): string
    {
        return (string) $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function setCreatedAt(int $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|ProductCategory[]
     */
    public function getProductCategories(): Collection
    {
        return $this->productCategories;
    }

    public function addProductCategory(ProductCategory $productCategory): self
    {
        if (!$this->productCategories->contains($productCategory)) {
            $this->productCategories[] = $productCategory;
            $productCategory->setProduct($this);
        }

        return $this;
    }

    public function removeProductCategory(ProductCategory $productCategory): self
    {
        if ($this->productCategories->removeElement($productCategory)) {
            // set the owning side to null (unless already changed)
            if ($productCategory->getProduct() === $this) {
                $productCategory->setProduct(null);
            }
        }

        return $this;
    }

    public function getProductProperty(): ?ProductProperty
    {
        return $this->productProperty;
    }

    public function setProductProperty(?ProductProperty $productProperty): self
    {
        // set the owning side of the relation if necessary
        if ($productProperty->getProduct() !== $this) {
            $productProperty->setProduct($this);
        }

        $this->productProperty = $productProperty;

        return $this;
    }
}
