<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Installation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'installation_date' => 'date',
    ];

    public function installationParameters(): HasMany
    {
        return $this->hasMany(InstallationParameter::class);
    }

    public function installationTreatmentSchemes(): HasMany
    {
        return $this->hasMany(InstallationTreatmentScheme::class);
    }
}
