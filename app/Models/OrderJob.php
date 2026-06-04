<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use App\Utility\Traits\ModelQueryTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Src\Company\Modules\OrderJob\Observers\OrderJobObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(OrderJobObserver::class)]
class OrderJob extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, ModelQueryTrait;
    
    protected $fillable = [
        'id',
        'job_no',
        'order_id',
        'product_id',
        'product_name',
        'design_by',
        'print_by',
        'status',
        'cancellation_reason',
        'measurement_unit',
        'width',
        'height',
        'qty',
        'sq_ft',
        'rate',
        'amount',
        'pesting_charge',
        'fitting_charge',
        'transportation_charge',
        'narration',
        'created_at',
        'updated_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function wastages()
    {
        return $this->hasMany(OrderJobWastage::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('order_job_image')->singleFile()->useFallbackUrl(asset('build/panel/images/thumbnail.jpg'));
    }

    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('order_job_image');
    }
    public function designBy()
    {
        return $this->belongsTo(Staff::class, 'design_by');
    }
    public function printBy()
    {
        return $this->belongsTo(Staff::class, 'print_by');
    }
}
