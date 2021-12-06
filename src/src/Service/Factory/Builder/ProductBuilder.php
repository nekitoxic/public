<?php
namespace App\Service\Factory\Builder;

use App\Entity\Product;
use App\Service\Factory\Interface\FactoryBuilderInterface;

class ProductBuilder implements FactoryBuilderInterface
{
    public function build(object $product, array $data): Product
    {
        return $product
                ->setName($data['name'])
                ->setPrice($data['price']);
    }
}