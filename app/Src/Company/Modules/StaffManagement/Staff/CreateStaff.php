<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\StaffManagement\Staff;

use App\Models\Role;
use App\Models\StaffRole;
use App\Src\Company\Modules\StaffManagement\Staff\Form\StaffForm;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateStaff extends Component
{
    use WithFileUploads;

    public StaffForm $form;

    public $canCreate;

    protected ?string $moduleUniqueName = 'company.staff';

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
        $this->form->createStaff();

        flashAlert(__('app.panel.store', ['name' => __('company.staff-management.staff')]), 'success');
        $this->redirectRoute('company.staff-management.staff.index');
    }

    public function render(): View
    {
        $title = __('app.panel.create_name', ['name' => __('company.staff-management.staff')]);
        $staffRoles = StaffRole::where('company_id', Auth::user()->company_id)->get();
        $assignRole = Role::where('company_id', Auth::user()->company_id)->get();

        if ($this->canCreate) {
            return view('company::StaffManagement.Staff.views.form', [
                'title' => $title,
                'staffRoles' => $staffRoles,
                'assignRoles' => $assignRole,
                'existingLogo' => '',
            ])->layout(
                'panel::layout.app',
                [
                    'breadcrumb' => [
                        [str()->plural(__('company.staff-management.staff')), route('company.staff-management.staff.index')],
                        [__('app.panel.create'), route('company.staff-management.staff.create')],
                    ],
                    'title' => $title,
                ]
            );
        } else {
            abort(403);
        }
    }
}
