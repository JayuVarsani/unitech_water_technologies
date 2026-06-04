<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\StaffManagement\StaffRole\Form;

use App\Models\Staff;
use App\Models\StaffRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class StaffRoleForm extends Form
{
    #[Validate]
    public $id = 0;

    #[Validate]
    public $name;

    public function createStaffRole(): void
    {
        $this->validate();
        StaffRole::create($this->getValues());
    }

    public function rules(): array
    {
        $this->resetErrorBag();
        return [
            'name' => ['required', 'max:30', Rule::unique(StaffRole::class, 'name')
                ->where(fn ($query) => $query->where('company_id', Auth::user()->company_id))
                ->ignore($this->id, 'id')],
        ];
    }

    public function setstaffRole(StaffRole $staffRole): void
    {
        $this->id = $staffRole->id;
        $this->fill([
            'name' => $staffRole->name,
        ]);
    }

    public function update(StaffRole $staffRole): void
    {
        $this->validate();
        $values = $this->getValues();
        $staffRole->update($values);
        Staff::where('staff_role_id', $staffRole->id)->update([
            'staff_role_name' => $values['name'] ?? $staffRole->name,
        ]);
    }

    protected function getValues(): array
    {
        return [
            'name' => ucfirst(trim((string) ($this->name ?? ''))),
            'company_id' => Auth::user()->company_id,
        ];
    }
}
