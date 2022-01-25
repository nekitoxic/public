<?php
namespace App\Service\Factory;

use App\Enum\ResponseType;
use App\Service\Factory\Builder\CategoryBuilder;
use App\Service\Factory\Builder\ProductBuilder;
use App\Service\Factory\Builder\ProductPropertyBuilder;
use App\Service\Factory\Interface\FactoryBuilderInterface;

class EntityFactory implements AbstractFactoryInteface
{
    public static function createCategory(): FactoryBuilderInterface
    {
        return new CategoryBuilder();
    }

    public static function createProduct(): FactoryBuilderInterface
    {
        return new ProductBuilder();
    }

    public static function createProductProperty(): FactoryBuilderInterface
    {
        return new ProductPropertyBuilder();
    }

    public static function createProductCategory(): FactoryBuilderInterface
    {
        return new ProductPropertyBuilder();
    }

    public static function getBuilderByType(ResponseType $type): FactoryBuilderInterface
    {
        return match ($type) {
            ResponseType::CategoryType          => self::createCategory(),
            ResponseType::ProductType           => self::createProduct(),
            ResponseType::ProductPropertyType   => self::createProductProperty(),
        };
    }
}