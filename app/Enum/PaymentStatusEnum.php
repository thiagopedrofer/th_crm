<?php

namespace App\Enum;

enum PaymentStatusEnum: string
{
    case CONFIRMED = 'confirmed';
    case PENDING = 'pending';
    case AWAITING_PAYMENT = 'awaiting_payment';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}