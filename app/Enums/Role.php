<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: string
{
    case SuperAdmin = 'super-admin';
    case Admin = 'admin';
    case Staff = 'staff';

    /**
     * @return list<Role>
     */
    public static function roles(): array
    {
        return [
            self::Admin,
            self::Staff,
        ];
    }
}
