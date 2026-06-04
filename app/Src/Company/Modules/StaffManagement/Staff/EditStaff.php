<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\StaffManagement\Staff;

use App\Models\Role;
use App\Models\Staff;
use App\Models\StaffRole;
use App\Src\Company\Modules\StaffManagement\Staff\Form\StaffForm;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditStaff extends Component
{
    use WithFileUploads;

    #[Locked]
    public Staff $staff;

    public StaffForm $form;

    public $canEdit;

    protected ?string $moduleUniqueName = 'company.staff';

    public function mount(Staff $staff): void
    {
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->staff = $staff;
        $this->form->setStaff($staff);
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->update($this->staff);
        flashAlert(__('app.panel.update', ['name' => __('company.staff-management.staff')]), 'success');
        $this->redirectRoute('company.staff-management.staff.index');
    }

    public function render(): View
    {
        $title = __('app.panel.edit_name', ['name' => __('company.staff-management.staff')]);
        $staffRoles = StaffRole::where('company_id', Auth::user()->company_id)->get();
        $assignRole = Role::where('company_id', Auth::user()->company_id)->get();

        if ($this->canEdit) {
            return view(
                'company::StaffManagement.Staff.views.form',
                ['title' => $title, 'staffRoles' => $staffRoles, 'assignRoles' => $assignRole, 'existingLogo' => $this->staff->getfirstMediaUrl('staff_image')])
                ->layout('panel::layout.app', [
                    'breadcrumb' => [
                        [str()->plural(__('company.staff-management.staff')), route('company.staff-management.staff.index')],
                        [__('app.panel.edit'), route('company.staff-management.staff.edit', $this->staff->id)],
                    ],
                    'title' => $title,

                ]);
        } else {
            abort(403);
        }
    }
}
