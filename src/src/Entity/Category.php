<?php
namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    public const MY_GROUP = "category";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Ignore]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups([self::MY_GROUP])]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: ProductCategory::class, orphanRemoval: true)]
    #[Groups([self::MY_GROUP])]
    private Collection $productCategories;

    #[ORM\Column(type: Types::STRING, unique:TRUE, options:["length" => 64])]
    #[Groups([self::MY_GROUP, ProductCategory::MY_GROUP])]
    private string $uuid;

    public function __construct()
    {
        $this->uuid = Uuid::v6();
        $this->productCategories = new ArrayCollection();
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

    public function getId(): int
    {
        return $this->id;
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
            $productCategory->setCategory($this);
        }

        return $this;
    }

    public function removeProductCategory(ProductCategory $productCategory): self
    {
        if ($this->productCategories->removeElement($productCategory)) {
            // set the owning side to null (unless already changed)
            if ($productCategory->getCategory() === $this) {
                $productCategory->setCategory(null);
            }
        }

        return $this;
    }

    public function getIdentifier(): string
    {
        return (string) $this->uuid;
    }
}
