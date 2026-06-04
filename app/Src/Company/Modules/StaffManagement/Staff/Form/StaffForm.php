<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\StaffManagement\Staff\Form;

use App\Models\Role;
use App\Models\Staff;
use App\Models\StaffRole;
use App\Utility\Enums\CompanyTypeEnum;
use App\Utility\Enums\StatusEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class StaffForm extends Form
{
    #[Validate]
    public $id = 0;

    #[Validate]
    public $name;

    // #[Validate]
    public $email;

    #[Validate(as: "mobile number")]
    public $contactNumber;

    #[Validate]
    public $address;

    #[Validate(as: "staff designation")]
    public $staffRole;

    #[Validate]
    public $joiningDate;

    #[Validate(as: "salary")]
    public $currentSalary;

    #[Validate]
    public $password;

    // #[Validate]
    public $accountNumber;

    // #[Validate]
    public $ifscCode;

    #[Validate]
    public $staffDocument;

    #[Validate]
    public $status = false;

    // #[Validate]
    public $lastWorkingDay;

    public $city;

    public $state;

    public $pincode;

    #[Validate]
    public $staff_image;

    #[Validate]
    public $assignRole;

    public function createStaff(): void
    {
        $this->validate();
        $staff = Staff::create($this->getValues());
        if ($this->staff_image) {
            $staff->addMedia($this->staff_image)->toMediaCollection('staff_image');
        }
        $staff->roles()->sync($this->assignRole);
    }

    public function rules(): array
    {
        $this->resetErrorBag();
        $rules = [
            'name' => ['required', 'max:30'],
            // 'email' => ['nullable', 'email', 'max:30'],
            'email' => ['nullable', 'email', 'max:100', Rule::unique(Staff::class, 'email')->ignore($this->id, 'id')],
            'contactNumber' => ['required', 'numeric' ,'digits_between:8,12', 'gt:0', Rule::unique(Staff::class, 'contact_number')->ignore($this->id, 'id')],
            'staffRole' => ['required'],
            'joiningDate' => ['required', 'date'],
            // 'lastWorkingDay' => ['required_if:status,false'],
            // 'accountNumber' => ['required'],
            // 'ifscCode' => ['required'],
            'assignRole' => ['required'],
            'staff_image' => ['nullable', 'file', 'image', 'max:' . config('media-library.max_file_size')],
            'staffDocument' => ['nullable', 'file', 'image', 'max:' . config('media-library.max_file_size')],
            'currentSalary' => ['nullable', 'numeric', 'min:0', 'max:999999'],

        ];

        if (! $this->id) {
            return array_merge($rules, ['password' => ['required', 'max:100', 'alpha_num', 'min:6']]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'contactNumber.unique' => 'This mobile number is already assigned to another company.',
            'email.unique' => 'This email is already used by another staff member.',
        ];
    }

    public function setStaff(Staff $staff): void
    {
        $this->id = $staff->id;
        $data = Staff::where('id', $staff->id)->first();

        $this->fill([
            'name' => $staff->name,
            'email' => $staff->email,
            'contactNumber' => $staff->contact_number,
            'address' => $staff->address,
            'city' => $staff->city,
            'state' => $staff->state,
            'pincode' => $staff->pincode,
            'staffRole' => $staff->staff_role_id,
            'assignRole' => $staff->assign_role_id,
            'joiningDate' => $staff->joining_date,
            'currentSalary' => $staff->salary,
            'accountNumber' => $staff->account_number,
            'ifscCode' => $staff->ifsc_code,
            'status' => $data['status'],
            'lastWorkingDay' => $staff->last_working_day,
        ]);
    }

    public function update(Staff $staff): void
    {
        $this->validate();

        $staff->update($this->getValues());
        if ($this->staff_image) {
            $staff->addMedia($this->staff_image)->toMediaCollection('staff_image');
        }
        $staff->roles()->sync($this->assignRole);
    }

    protected function getValues(): array
    {

        $staffRole = StaffRole::where('id', $this->staffRole)->first();
        if ($this->assignRole !== '') {
            $assignRole = Role::where('id', $this->assignRole)->first();
        }

        $reuestedData = [
            'company_id' => Auth::user()->company_id,
            'name' => ucfirst(trim((string) ($this->name ?? ''))),
            'email' => $this->email,
            'contact_number' => $this->contactNumber,
            'address' => ucfirst($this->address ?? ''),
            'city' => ucfirst($this->city ?? ''),
            'state' => ucfirst($this->state ?? ''),
            'pincode' => $this->pincode,
            'staff_role_id' => $this->staffRole,
            'staff_role_name' => $staffRole->name,
            'assign_role_id' => is_numeric($this->assignRole) ? (int) $this->assignRole : 0,
            'assign_role_name' => $assignRole->name ?? '',
            'joining_date' => $this->joiningDate,
            'last_working_day' => $this->lastWorkingDay,
            'salary' => $this->currentSalary,
            'account_number' => $this->accountNumber,
            'ifsc_code' => $this->ifscCode,
            'type' => CompanyTypeEnum::Staff->value,
            'status' => $this->status === true || $this->status === 1 ? StatusEnum::Active->value : StatusEnum::InActive->value,
        ];

        if ($this->password !== null) {
            $reuestedData['password'] = $this->password;
        }

        return $reuestedData;
    }
}
