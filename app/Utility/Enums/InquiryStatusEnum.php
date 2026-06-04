<?php

declare(strict_types=1);

namespace App\Utility\Enums;

enum InquiryStatusEnum: string
{
    case Open = 'open';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Open => '<span class="badge badge-lg bg-light-primary fw-semibold text-primary">Open</span>',
            self::Confirmed => '<span class="badge badge-lg bg-light-warning fw-semibold text-warning">Confirmed</span>',
            self::Cancelled => '<span class="badge badge-lg bg-light-danger fw-semibold text-danger">Cancelled</span>',
        };
    }
    public function getName(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Confirmed => 'Confirmed',
            self::Cancelled => 'Cancelled',
        };
    }
}
