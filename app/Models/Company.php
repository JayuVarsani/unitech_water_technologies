<?php

declare(strict_types=1);

namespace App\Models;

use App\Utility\Traits\ModelQueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Company extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, ModelQueryTrait;

    protected $guarded = [];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('company_logo')->singleFile();
    }

    public function registerMediaConversions(?\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('compressed')
            ->format('jpg') // Ensure the image is converted to JPG for better compression
            ->quality(75) // Set the compression quality (75 is a good balance)
            ->performOnCollections('company_logo'); // Apply this conversion only to the 'job_image' collection
    }

    public function company_staff(): HasOne
    {
        return $this->hasOne(Staff::class, 'company_id');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
