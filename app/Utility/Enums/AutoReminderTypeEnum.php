<?php

declare(strict_types=1);

namespace App\Utility\Enums;

enum AutoReminderTypeEnum: string
{
    case OnceInWeek = 'onceinweek';
    case OnceInMonth = 'onceinmonth';

    public static function label(): array
    {
        return [
            'OnceInWeek' => 'Once In Week',
            'OnceInMonth' => 'Once In Month',
        ];
    }
}
