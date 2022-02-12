<?php
namespace App\Service\Factory;

use App\Service\Factory\Builder\BuilderInterface;

interface EntityFactoryInteface
{
    public static function createCategory(): BuilderInterface;

    public static function createProduct(): BuilderInterface;

    public static function createProductProperty(): BuilderInterface;
}