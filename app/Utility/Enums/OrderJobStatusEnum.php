<?php

declare(strict_types=1);

namespace App\Utility\Enums;

enum OrderJobStatusEnum: string
{
    case Design = 'design';
    case Printed = 'printed';
    case Completed = 'completed';
    // case Fitting = 'fitting';
    // case Pasting = 'pasting';
    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Design => '<span class="badge badge-lg bg-light-info text-info">Design</span>',
            self::Printed => '<span class="badge badge-lg bg-light-warning text-warning">Printed</span>',
            self::Completed => '<span class="badge badge-lg bg-light-success text-success">Completed</span>',
            self::Cancelled => '<span class="badge badge-lg bg-light-danger text-danger">Cancelled</span>',
        };
    }
    public function getName(): string
    {
        return match ($this) {
            self::Design => 'Design',
            self::Printed => 'Printed',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }
}
