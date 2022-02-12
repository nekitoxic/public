<?php
namespace App\Service\Factory\Builder;

use App\Entity\Product;

class ProductBuilder implements BuilderInterface
{
    public function build(object $product, array $data): Product
    {
        return $product
                ->setName($data['name'])
                ->setPrice($data['price']);
    }

    public static function create(): object
    {
        return new Product();
    }
}