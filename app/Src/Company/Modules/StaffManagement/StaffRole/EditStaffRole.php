<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\StaffManagement\StaffRole;

use App\Models\StaffRole;
use App\Src\Company\Modules\StaffManagement\StaffRole\Form\StaffRoleForm;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditStaffRole extends Component
{
    #[Locked]
    public StaffRole $staffRole;

    public StaffRoleForm $form;

    public $canEdit;

    protected ?string $moduleUniqueName = 'company.staff-role';

    public function mount(StaffRole $staffRole): void
    {
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->staffRole = $staffRole;
        $this->form->setStaffRole($staffRole);
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->update($this->staffRole);
        flashAlert(__('app.panel.update', ['name' => __('company.staff-management.staff-role')]), 'success');
        $this->redirectRoute('company.staff-management.staff-role.index');
    }

    public function render(): View
    {
        $title = __('app.panel.edit_name', ['name' => __('company.staff-management.staff-role')]);

        if ($this->canEdit) {
            return view('company::StaffManagement.StaffRole.views.form', ['title' => $title])
                ->layout('panel::layout.app', [
                    'breadcrumb' => [
                        [str()->plural(__('company.staff-management.staff-role')), route('company.staff-management.staff-role.index')],
                        [__('app.panel.edit'), route('company.staff-management.staff-role.edit', $this->staffRole->id)],
                    ],
                    'title' => $title,
                ]);
        } else {
            abort(403);
        }

    }
}
