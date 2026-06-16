<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visit extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_COMPLETED = 'completed';

    protected $guarded = [];

    protected $casts = [
        'visit_date' => 'date',
        'mcf_last_replaced' => 'date',
        'sort_order' => 'integer',
    ];

    public function amc(): BelongsTo
    {
        return $this->belongsTo(Amc::class);
    }

    public function amcVisitMonth(): BelongsTo
    {
        return $this->belongsTo(AmcVisitMonth::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}
