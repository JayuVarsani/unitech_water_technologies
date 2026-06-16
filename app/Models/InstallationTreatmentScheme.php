<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstallationTreatmentScheme extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function installation(): BelongsTo
    {
        return $this->belongsTo(Installation::class);
    }

    public function treatmentScheme(): BelongsTo
    {
        return $this->belongsTo(TreatmentScheme::class);
    }
}
