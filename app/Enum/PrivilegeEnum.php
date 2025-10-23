<?php

namespace App\Enum;

enum PrivilegeEnum: int
{
    case ADMIN = 1;
    case SUPER_ADMIN = 2;
    case SELLER = 3;

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function labels(): array
    {
        return array_map(fn($case) => $case->name, self::cases());
    }

}