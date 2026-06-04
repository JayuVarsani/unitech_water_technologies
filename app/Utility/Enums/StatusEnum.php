<?php

declare(strict_types=1);

namespace App\Utility\Enums;

use App\Utility\Traits\CommonEnumTrait;
use EmreYarligan\EnumConcern\EnumConcern;

enum StatusEnum: int
{
    use CommonEnumTrait, EnumConcern;

    case Active = 1;

    case InActive = 0;

    public function isActive(): int
    {
        return $this === StatusEnum::Active ? 1 : 0;
    }
}
