<?php

namespace App\Enums;

enum Status: string {
    case AVAILABLE = 'available';
    case PENDING = 'pending';
    case SOLD = 'sold';

    public static function values()
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
