<?php

declare(strict_types=1);

namespace App\Utility\Enums;

enum DamageTypeEnum: string
{
    case WholeProduct = 'whole_product';
    case Materials = 'materials';

    public function getLabel(): string
    {
        return match ($this) {
            self::WholeProduct => '<span class="badge text-bg-primary text-white">Whole Product</span>',
            self::Materials => '<span class="badge text-bg-secondary text-white">Materials</span>',
        };
    }
    public function getName(): string
    {
        return match ($this) {
            self::WholeProduct => 'Whole Product',
            self::Materials => 'Materials',
        };
    }
}
