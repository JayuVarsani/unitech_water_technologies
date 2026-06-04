<?php

declare(strict_types=1);

namespace App\Models;

use App\Utility\Traits\ModelQueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Module extends Model
{
    use HasFactory, ModelQueryTrait;

    protected $casts = ['sub_routes' => 'json', 'actions' => 'json'];

    public static function getModulesByUser()
    {
        $for = request()->user()->getMorphClass();

        return Cache::remember('panel_modules_'.$for, getCacheTime(), function () use ($for) {
            return Module::where(['parent_id' => null, 'for' => $for])
                ->Active()
                ->with('children', function ($child) {
                    return $child->orderBy('position')->Active();
                })->orderBy('position')->get();
        });
    }

    public static function getPermissionWithName(array $permissions, $parent = '')
    {
        foreach ($permissions as $key => $permission) {
        }
    }

    public function children(): HasMany
    {
        return $this->hasMany(Module::class, 'parent_id');
    }
}
