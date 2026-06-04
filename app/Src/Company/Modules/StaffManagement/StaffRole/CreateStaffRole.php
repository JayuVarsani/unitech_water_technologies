<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\StaffManagement\StaffRole;

use App\Src\Company\Modules\StaffManagement\StaffRole\Form\StaffRoleForm;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreateStaffRole extends Component
{
    public StaffRoleForm $form;

    public $canCreate;

    protected ?string $moduleUniqueName = 'company.staff-role';

    public function mount()
    {
        $this->canCreate = $this->hasPermission(type: 'create');
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->createStaffRole();

        flashAlert(__('app.panel.store', ['name' => __('company.staff-management.staff-role')]), 'success');
        $this->redirectRoute('company.staff-management.staff-role.index');
    }

    public function render(): View
    {
        $title = __('app.panel.create_name', ['name' => __('company.staff-management.staff-role')]);

        if ($this->canCreate) {
            return view('company::StaffManagement.StaffRole.views.form', [
                'title' => $title,
            ])->layout(
                'panel::layout.app',
                [
                    'breadcrumb' => [
                        [(__('company.staff-management.staff-role')), route('company.staff-management.staff-role.index')],
                        [__('app.panel.create'), route('company.staff-management.staff-role.create')],
                    ],
                    'title' => $title,
                ]
            );
        } else {
            abort(403);
        }
    }
}
