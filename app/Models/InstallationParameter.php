<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstallationParameter extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'value' => 'decimal:4',
    ];

    public function installation(): BelongsTo
    {
        return $this->belongsTo(Installation::class);
    }

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(Parameter::class);
    }
}
