<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmcVisitMonth extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'visit_month' => 'date',
        'sort_order' => 'integer',
    ];

    public function amc(): BelongsTo
    {
        return $this->belongsTo(Amc::class);
    }
}
