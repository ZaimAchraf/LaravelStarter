<?php

namespace App\Enums;

enum UserRole: int
{
    case SUPER_ADMIN = 1;
    case MANAGER = 2;
    case USER = 3;

    public function label(): string
    {
        return match($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::MANAGER => 'Manager',
            self::USER => 'User'
        };
    }

    public function permissions(): array
    {
        return match($this) {
            self::SUPER_ADMIN => ['manage-all', 'access-dashboard', 'manage-users'],
            self::MANAGER => ['access-dashboard', 'manage-users'],
            self::USER => ['access-dashboard']
        };
    }
}