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

class CreateRolePermission extends Component
{
    #[Validate]
    public string $name;

    public array $permission;

    public $modules;

    protected RolePermissionService $service;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
        ];
    }

    // public function rules(): array
    // {
    //     return [
    //         'name' => ['required', 'string', 'max:100'],
    //     ];
    // }
    public function boot(RolePermissionService $service): void
    {
        $this->service = $service;
    }

    public function mount(): void
    {
        $this->modules = $this->service->getModules();
    }

    // public function createRole(): void
    // {
    //    // $this->validate();

    //     $role = Role::create(['name' => $this->name, 'company_id' => Auth::user()->company_id]);

    //     $this->service->syncPermission($this->permission, $role);
    //     flashAlert(__('app.panel.store', ['name' => __('company.role-permission.title')]), 'success');
    //     $this->redirectRoute('company.staff-management.role-permission.index');
    // }

    public function createRole(): void
    {
        $this->resetErrorBag();
        $this->validate();
        try {
            $role = Role::create([
                'name' => Auth::user()->company_id.'_'.ucfirst($this->name),
                'guard_name' => 'company',
                'company_id' => Auth::user()->company_id,
            ]);
            $this->service->syncPermission($this->permission, $role);
            flashAlert(__('app.panel.store', ['name' => __('company.role-permission.title')]), 'success');
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
        $title = __('app.panel.create_name', ['name' => __('company.role-permission.title')]);

        return view('company::RolePermission.views.create-role', ['title' => $title])
            ->layout('panel::layout.app', [
                'title' => $title,
                'breadcrumb' => [
                    [__('company.role-permission.title'), route('company.staff-management.role-permission.index')],
                    [__('app.panel.create'), route('company.staff-management.role-permission.create')],
                ],
            ]);
    }
}
