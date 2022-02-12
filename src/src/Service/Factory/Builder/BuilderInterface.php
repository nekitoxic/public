<?php
namespace App\Service\Factory\Builder;

interface BuilderInterface
{
    public function build(object $entity, array $data): object;

    public static function create(): object;
}