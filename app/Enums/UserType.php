<?php

namespace App\Enums;

enum UserType: string
{
    case ADMIN = 'admin';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Admin User',
        };
    }
}