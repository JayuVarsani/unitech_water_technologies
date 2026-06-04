<?php

declare(strict_types=1);

namespace App\Utility\Enums;

enum CustomerRegisterTypeEnum: string
{
    case Regular = 'regular';
    case Consumer = 'consumer';
    case Unregistered = 'unregistered';
    case Composition = 'composition';

    public static function label(): array
    {
        return [
            'Regular' => 'Regular',
            'Consumer' => 'Consumer',
            'Unregistered' => 'Un Registered',
            'Composition' => 'Composition',
        ];
    }
}
