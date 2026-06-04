<?php

declare(strict_types=1);

namespace App\Utility\Traits;

use Exception;

trait CommonEnumTrait
{
    public static function inRule(): string
    {
        return self::all()->values()->implode(',');
    }

    /**
     * @throws Exception
     */
    public function getName(): string
    {
        if (! is_string($this->value)) {
            throw new Exception('Only String is accepted');
        }

        return ucwords(str_replace('_', ' ', $this->value));
    }

    public function getOppositeStatus(): int
    {
        return match ($this) {
            self::Active => self::InActive->value,
            self::InActive => self::Active->value,
        };
    }
}
