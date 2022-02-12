<?php
namespace App\Entity;

use App\Repository\ProductCategoryRepository;
use App\Trait\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProductCategoryRepository::class)]
class ProductCategory
{
    public const MY_GROUP = "product-category";

    use EntityTrait;

    #[ORM\Column(type: Types::STRING, unique:TRUE, options:["length" => 64])]
    #[Groups([Product::MY_GROUP, Category::MY_GROUP, self::MY_GROUP])]
    private string $uuid;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'productCategories')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::MY_GROUP])]
    private Category $category;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productCategories')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::MY_GROUP])]
    private Product $product;

    public function __construct()
    {
        $this->uuid = (string) Uuid::v6();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): self
    {
        $this->uuid = (string) $uuid;

        return $this;
    }

    public function getIdentifier(): string
    {
        return (string) $this->uuid;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
