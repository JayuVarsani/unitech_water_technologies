<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\RolePermission;

use App\Models\Role;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Exceptions\RoleAlreadyExists;

class EditRolePermission extends Component
{
    #[Validate]
    public string $name;

    public $modules;

    public array $permission;

    public $id;

    protected RolePermissionService $service;

    public function boot(RolePermissionService $service): void
    {
        $this->service = $service;
    }

    public function mount($id): void
    {
        $this->id = $id;
        $role = $this->service->getRole($id);
        $this->modules = $this->service->getModules();

        $this->name = $role->name;
        $role->permissions->pluck('name')->each(function ($permission) {
            $parts = explode('.', $permission);
            $user = $parts[0] ?? null;
            $module = $parts[1] ?? null;
            $permissionPart = $parts[2] ?? null;
            if ($user && $module && $permissionPart) {
                $this->permission[$user][$module][] = $permissionPart;
            }
        });
    }

    public function rules(): array
    {
        $this->resetErrorBag();
        return [
            'name' => [
                'required',
                'string',
                'max:100',
            ],
        ];
    }

    public function createRole(): void
    {
        $this->validate();
        try {
            $role = Role::findOrFail($this->id);
            $role->update(['name' => Auth::user()->company_id . '_' . ucfirst($this->name)]);
            $this->service->syncPermission($this->permission, $role);
            flashAlert(__('app.panel.update', ['name' => __('company.role-permission.title')]), 'success');
            $this->redirectRoute('company.staff-management.role-permission.index');
        } catch (Exception $exception) {
            if ($exception instanceof RoleAlreadyExists) {
                flashAlert('Role Name is already taken', 'danger');

                return;
            }
            flashAlert($exception->getMessage(), 'danger');
        }

    }

    public function render(): View
    {

        $title = __('app.panel.edit_name', ['name' => __('company.role-permission.title')]);

        return view('company::RolePermission.views.edit-role', ['title' => $title])
            ->layout('panel::layout.app', [
                'title' => $title,
                'breadcrumb' => [
                    [__('company.role-permission.title'), route('company.staff-management.role-permission.index')],
                    [__('app.panel.edit'), route('company.staff-management.role-permission.edit', $this->id)],
                ],
            ]);
    }
    //     public function updatePermissions($modelName, $selectedValues)
    // {
    //     $this->permission['company'][$modelName] = $selectedValues;
    // }

    // public function updatePermissions($data)
    // {
    //     $modelName = $data['modelName'];
    //     $selectedValues = $data['selectedValues'];

    //     // Ensure Livewire property updates correctly
    //     $this->permission['company'][$modelName] = is_array($selectedValues) ? $selectedValues : [];
    // }
}
