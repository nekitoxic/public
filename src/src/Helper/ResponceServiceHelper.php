<?php

namespace App\Helper;

use App\Enum\ResponseType;
use App\Enum\ErrorMessage;

class ResponceServiceHelper
{
    private const CATEGORY_TYPE          = ['name'];
    private const PRODUCT_TYPE           = ['name', 'price'];
    private const PRODUCT_PROPERTY_TYPE  = ['product', 'weight', 'height'];

    public static function dataValidate(ResponseType $type, array $data): ?ErrorMessage
    {
        if (!self::validateRequest($type, $data)) {
            return ErrorMessage::SyntaxError;
        }

        if (!self::validateDataFromRequest($data)) {
            return ErrorMessage::InvalidData;
        }

        return null;
    }

    private static function validateRequest(ResponseType $type, array $data): bool
    {
        return (self::getRequiredJsonKeys($type) === array_keys($data));
    }

    private static function validateDataFromRequest(array $data): bool
    {
        foreach ($data as $row) {
            if (empty($row)) {
                return false;
            }
        }

        return true;
    }

    private static function getRequiredJsonKeys(ResponseType $type): array
    {
        return match($type) {
            ResponseType::CategoryType => self::CATEGORY_TYPE,
            ResponseType::ProductType => self::PRODUCT_TYPE,
            ResponseType::ProductPropertyType => self::PRODUCT_PROPERTY_TYPE,
            ResponseType::Undefined => [],
        };
    }
}