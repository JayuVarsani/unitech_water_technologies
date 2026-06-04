<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use App\Utility\Traits\ModelQueryTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InquiryJob extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, ModelQueryTrait;

    protected $fillable = [
        'job_no',
        'inquiry_id',
        'product_id',
        'measurement_unit',
        'product_name',
        'width',
        'height',
        'qty',
        'sq_ft',
        'rate',
        'amount',
        'pesting_charge',
        'fitting_charge',
        'narration',
    ];

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('inquiry_job_image')->singleFile()->useFallbackUrl(asset('build/panel/images/thumbnail.jpg'));
    }

    // public function registerMediaConversions(?Media $media = null): void
    // {
    //     $this->addMediaConversion('compressed')
    //         ->width(368)
    //         ->height(232)
    //         ->format('jpg')
    //         // ->quality(50)
    //         ->performOnCollections('inquiry_job_image');
    // }

    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('inquiry_job_image');
    }
}
