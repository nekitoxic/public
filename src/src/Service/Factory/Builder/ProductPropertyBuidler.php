<?php
namespace App\Service\Factory\Builder;

use App\Entity\ProductProperty;
use App\Service\Factory\Interface\FactoryBuilderInterface;

class ProductPropertyBuidler implements FactoryBuilderInterface
{
    public function build(object $productProperty, array $data): ProductProperty
    {
        return
            $productProperty
                ->setWeight($data['weight'])
                ->setHeight($data['height']);
    }
}