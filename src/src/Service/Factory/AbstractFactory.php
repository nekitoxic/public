<?php
namespace App\Service\Factory;

use App\Service\Factory\Interface\FactoryBuilderInterface;

interface AbstractFactoryInteface
{
    public static function createCategory(): FactoryBuilderInterface;

    public static function createProduct(): FactoryBuilderInterface;

    public static function createProductProperty(): FactoryBuilderInterface;
}