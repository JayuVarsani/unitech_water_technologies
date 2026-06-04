<?php

declare(strict_types=1);

namespace App\Utility\Enums;

enum OrderStatusEnum: string
{
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
    case Printed = 'printed';
    case Completed = 'completed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Printed => '<span class="badge badge-lg bg-light-primary text-primary">Printed</span>',
            self::Confirmed => '<span class="badge badge-lg bg-light-warning text-warning">Confirmed</span>',
            self::Cancelled => '<span class="badge badge-lg bg-light-danger text-danger">Cancelled</span>',
            self::Completed => '<span class="badge badge-lg bg-light-success text-success">Completed</span>',
        };
    }
    public function getName(): string
    {
        return match ($this) {
            self::Printed => 'Printed',
            self::Confirmed => 'Confirmed',
            self::Cancelled => 'Cancelled',
            self::Completed => 'Completed',
        };
    }
}
