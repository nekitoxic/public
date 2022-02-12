<?php
namespace App\Service\Factory\Builder;

use App\Entity\Category;

class CategoryBuilder implements BuilderInterface
{
    public function build(object $category, array $data): Category
    {
        return $category->setName($data['name']);
    }

    public static function create(): object
    {
        return new Category();
    }
}