<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_id',
        'guard_name',
    ];

    /**
     * Get the user's first name.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: function ($val) {
                $underscorePosition = strpos($val, '_') ?? 0; // Find the position of the underscore

                return substr($val, $underscorePosition + 1);
            },
        );
    }
}
