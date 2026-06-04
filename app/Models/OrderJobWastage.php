<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderJobWastage extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'order_job_id',
        'damage_type',
        'note',
        'material_id',
        'width',
        'height',
        'created_at',
        'updated_at',
    ];

    public function orderJob()
    {
        return $this->belongsTo(OrderJob::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
