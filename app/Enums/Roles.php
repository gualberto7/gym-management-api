<?php

namespace App\Enums;

enum Roles: string {
    case SUPER_ADMIN = 'super_admin';
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case USER = 'user';

    public static function label(): array {
        return [
            self::SUPER_ADMIN,
            self::OWNER,
            self::ADMIN,
            self::USER,
        ];
    }
}
