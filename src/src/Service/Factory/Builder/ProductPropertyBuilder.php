<?php
namespace App\Service\Factory\Builder;

use App\Entity\ProductProperty;

class ProductPropertyBuilder implements BuilderInterface
{
    public function build(object $productProperty, array $data): ProductProperty
    {
        return
            $productProperty
                ->setWeight($data['weight'])
                ->setHeight($data['height']);
    }

    public static function create(): object
    {
        return new ProductProperty();
    }
}