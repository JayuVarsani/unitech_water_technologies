<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'visit_date' => 'date',
        'mcf_last_replaced' => 'date',
    ];
}
