<?php

declare(strict_types=1);

namespace App\Models;

use App\Utility\Traits\ModelQueryTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Moderator extends Authenticatable implements HasMedia
{
    use InteractsWithMedia, ModelQueryTrait, Notifiable;

    protected $casts = ['type' => 'string'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'type',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_image')->singleFile()->useFallbackUrl(mix('build/panel/images/user.png')->toHtml());
    }

    protected function password(): Attribute
    {
        return Attribute::make(set: fn ($value) => bcrypt($value));
    }
}
