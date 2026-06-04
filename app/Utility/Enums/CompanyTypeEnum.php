<?php

declare(strict_types=1);

namespace App\Utility\Enums;

enum CompanyTypeEnum: string
{
    case Admin = 'admin';
    case Staff = 'staff';

    public function getLabel(): string
    {
        return match ($this) {
            self::Admin => '<span class="badge text-bg-success text-white">Admin</span>',
            self::Staff => '<span class="badge text-bg-info text-white">Staff</span>',
        };
    }
}
