<?php
namespace App\Enum;

enum ResponseType
{
    case CategoryType;
    case ProductType;
    case ProductPropertyType;
    case ProductCategoryType;
    case Undefined;
};