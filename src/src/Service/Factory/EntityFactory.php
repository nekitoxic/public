<?php
namespace App\Service\Factory;

use App\Enum\ResponseType;
use App\Service\Factory\Builder\CategoryBuilder;
use App\Service\Factory\Builder\ProductBuilder;
use App\Service\Factory\Builder\ProductPropertyBuilder;
use App\Service\Factory\Builder\BuilderInterface;
use Error;
use Symfony\Component\VarDumper\Exception\ThrowingCasterException;

class EntityFactory implements EntityFactoryInteface
{
    public static function createCategory(): BuilderInterface
    {
        return new CategoryBuilder();
    }

    public static function createProduct(): BuilderInterface
    {
        return new ProductBuilder();
    }

    public static function createProductProperty(): BuilderInterface
    {
        return new ProductPropertyBuilder();
    }

    public static function createProductCategory(): BuilderInterface
    {
        return new ProductPropertyBuilder();
    }

    public static function getBuilderByType(ResponseType $type): BuilderInterface
    {
        return match ($type) {
            ResponseType::CategoryType          => self::createCategory(),
            ResponseType::ProductType           => self::createProduct(),
            ResponseType::ProductPropertyType   => self::createProductProperty(),
            default => new ThrowingCasterException(new Error('Type of '. get_class($type) . 'is not defined!'))
        };
    }
}