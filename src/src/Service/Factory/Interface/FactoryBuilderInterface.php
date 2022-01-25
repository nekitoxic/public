<?php
namespace App\Service\Factory\Interface;

interface FactoryBuilderInterface
{
    public function build(object $entity, array $data): object;

    public static function create(): object;
}