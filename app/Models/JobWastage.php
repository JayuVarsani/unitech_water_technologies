<?php

declare(strict_types=1);

namespace App\Models;

use App\Utility\Traits\ModelQueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class JobWastage extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, ModelQueryTrait;

    protected $fillable = [
        'job_id',
        'damage_type',
        'note',
        'material_id',
        'width',
        'height',

    ];

    public function jobcard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function getMaterialNameAttribute()
    {
        return $this->material ? $this->material->material_name : 'N/A'; // Return 'N/A' if no material
    }

    public function getUnitNameAttribute()
    {
        return $this->material ? $this->material->unit_name : 'N/A';
    }
}
