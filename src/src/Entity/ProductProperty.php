<?php

namespace App\Entity;

use App\Repository\ProductPropertyRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProductPropertyRepository::class)]
class ProductProperty
{
    public const MY_GROUP = "product-property";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: 'uuid', unique:TRUE)]
    #[Groups([self::MY_GROUP, Product::MY_GROUP])]
    private Uuid $uuid;

    #[ORM\OneToOne(inversedBy: 'productProperty', targetEntity: Product::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::MY_GROUP])]
    private Product $product;

    #[ORM\Column(type: Types::INTEGER, options:["default" => 0])]
    #[Groups([self::MY_GROUP])]
    private int $weight = 0;

    #[ORM\Column(type: Types::INTEGER, options:["default" => 0])]
    #[Groups([self::MY_GROUP])]
    private int $height = 0;

    public function __construct()
    {
        $this->uuid = Uuid::v6();
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

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }
}
