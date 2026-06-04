<?php

declare(strict_types=1);

namespace App\Models;

use App\Utility\Traits\RolePermissionTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class Staff extends Authenticatable implements HasMedia
{
    use HasFactory, HasRoles, InteractsWithMedia, Notifiable, RolePermissionTrait;

    protected $table = 'staffs';

    protected $guard_name = 'company';

    protected $guarded = [];

    protected $rememberTokenName = false;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('staff_document');
        $this->addMediaCollection('profile_image')->singleFile()->useFallbackUrl(mix('build/panel/images/user.png')->toHtml());
        $this->addMediaCollection('staff_image')->singleFile()->useFallbackUrl(mix('build/panel/images/user.png')->toHtml());
    }

    protected function password(): Attribute
    {
        return Attribute::make(set: fn ($value) => bcrypt($value));
    }
    // public function staffrole()
    // {
    //     return $this->belongsTo(StaffRole::class,'staff_role_id');
    // }
}
