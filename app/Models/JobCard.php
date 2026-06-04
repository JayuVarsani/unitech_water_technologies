<?php

declare(strict_types=1);

namespace App\Models;

use App\Utility\Traits\ModelQueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class JobCard extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, ModelQueryTrait;

    protected $fillable = [
        'job_no',
        'company_id',
        'customer_id',
        'product_id',
        'job_date',
        'description',
        'product_name',
        'width',
        'height',
        'qty',
        'sq_ft',
        'rate',
        'amount',
        'job_status',
        'fitting_charge',
        'pesting_charge',
        'framing_charge',
        'transportation_charge',
        'final_total',
        'status',
        'staff_id',
        'is_inch',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('job_image')->singleFile()
        // ->useFallbackUrl(asset('build/panel/images/thumbnail.jpg'))
        ;
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('compressed')
            ->width(368)
            ->height(232)
            ->format('jpg')
            // ->quality(50)
            ->performOnCollections('job_image');
    }

    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('job_image');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function wastages()
    {
        return $this->hasMany(JobWastage::class, 'job_id');
    }
}
