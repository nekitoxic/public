<?php
namespace App\Service\Factory\Builder;

use App\Entity\ProductCategory;

class ProductCategoryBuilder implements BuilderInterface
{
    public function build(object $productProperty, array $data): ProductCategory
    {
        return
            $productProperty
                ->setWeight($data['weight'])
                ->setHeight($data['height']);
    }

    public static function create(): object
    {
        return new ProductCategory();
    }
}