<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: string
{
    case SuperAdmin = 'super-admin';
    case Admin = 'admin';
    case Worker = 'worker';

    /**
     * @return list<Role>
     */
    public static function roles(): array
    {
        return [
            self::Admin,
            self::Worker,
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin => 'Admin',
            self::Worker => 'Bublinář',
        };
    }
}
