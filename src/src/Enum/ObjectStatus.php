<?php
namespace App\Enum;

enum ObjectStatus: int
{
    case Create = 201;
    case Update = 200;
    case Delete = 202;
    case Error  = 404;
}