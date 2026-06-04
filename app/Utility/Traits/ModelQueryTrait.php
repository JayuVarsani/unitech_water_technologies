<?php

declare(strict_types=1);

namespace App\Utility\Traits;

use App\Utility\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Builder;

trait ModelQueryTrait
{
    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('status', StatusEnum::Active->value);
    }
}
