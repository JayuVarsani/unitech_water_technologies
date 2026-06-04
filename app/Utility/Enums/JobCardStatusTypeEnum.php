<?php

declare(strict_types=1);

namespace App\Utility\Enums;

enum JobCardStatusTypeEnum: string
{
    case Design = 'design';
    case Printed = 'printed';
    case Fitting = 'fitting';
    // case Framing = 'framing';
    case Pasting = 'pasting';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Pending = 'pending';

    public static function label(): array
    {
        return [
            'Design' => 'Design',
            'Printed' => 'Printed',
            'Fitting' => 'Fitting',
            // 'Framing' => 'Framing',
            'Pasting' => 'Pasting',
            'Completed' => 'Completed',
            'Cancelled' => 'Cancelled',
            'Pending' => 'Pending',
        ];
    }
}
