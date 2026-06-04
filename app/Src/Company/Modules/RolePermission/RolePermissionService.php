<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\RolePermission;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class RolePermissionService
{
    public function getRole($id): Role
    {
        return Role::with('permissions')->findOrFail($id);
    }

    public function getModules()
    {
        $user = Auth::user();
        $modules = config('modules.'.$user->getMorphClass());
        $moduleArray = [];
        foreach ($modules as $module) {
            if (! empty($module['children'])) {
                foreach ($module['children'] as $childrenModule) {
                    if ($childrenModule['index_route'] !== 'company.staff-management.role-permission.index') {
                        $moduleArray[] = $childrenModule;
                    }
                }
            } else {
                $moduleArray[] = $module;
            }
        }

        return $moduleArray;
    }

    public function syncPermission(array $permissions, Role $role): void
    {

        $permissions = collect($permissions)->dot()
            ->map(function ($module, $key) use ($role) {
                $moduleString = is_array($module) ? implode('.', $module) : $module;

                return [
                    'name' => substr_replace($key, '', strrpos($key, '.')).'.'.$moduleString,
                    'guard_name' => $role->guard_name,
                ];
            })->values();

        Permission::upsert($permissions->toArray(), ['name']);
        $role->syncPermissions($permissions->pluck('name'));
    }
}
