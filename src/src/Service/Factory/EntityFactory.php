<?php

namespace App\Service\Factory;

use App\Service\Factory\Builder\CategoryBuilder;
use App\Service\Factory\Builder\ProductBuilder;
use App\Service\Factory\Builder\ProductPropertyBuidler;
use App\Service\Factory\Interface\FactoryBuilderInterface;


class EntityFactory implements AbstractFactory
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
        return new ProductPropertyBuidler();
    }
}