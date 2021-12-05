<?php

namespace App\Service\Factory\Builder;

use App\Entity\Category;
use App\Service\Factory\Interface\FactoryBuilderInterface;

class CategoryBuilder implements FactoryBuilderInterface
{
    public function build(object $category, array $data): Category
    {
        return $category->setName($data['name']);
    }
}