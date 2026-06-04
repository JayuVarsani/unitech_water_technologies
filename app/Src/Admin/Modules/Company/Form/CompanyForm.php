<?php

declare(strict_types=1);

namespace App\Src\Admin\Modules\Company\Form;

use App\Models\Company;
use App\Models\Role;
use App\Models\Staff;
use App\Utility\Enums\CompanyTypeEnum;
use App\Utility\Enums\StatusEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Spatie\Permission\Models\Permission as SpatiePermission;

class CompanyForm extends Form
{
    #[Validate]
    public $name;

    #[Validate]
    public $email;

    #[Validate]
    public $contactNumber;

    #[Validate]
    public $password;

    #[Validate]
    public $address;

    #[Validate]
    public $gstNo;

    // #[Validate]
    public $status = false;

    public $id = 0;

    #[Validate]
    public $companyDocument;

    #[Validate]
    public $companyLogo;

    #[Validate]
    public $companyRegistorType;

    public $city;

    public $state;

    public $pincode;

    public $cinNumber;

    public function createCompany(): void
    {
        $this->validate();
        $data = $this->getValues();

        $company = Company::create([
            'name' => trim($data['name']),
            'gst_no' => $data['gstNo'],
            'address' => $data['address'],
            'status' => $this->status === true ? StatusEnum::Active->value : StatusEnum::InActive->value,
            'cin_number' => $data['cinNumber'],
            'company_registor_type' => $data['companyRegistorType'],
        ]);

        $company->company_staff()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'contact_number' => $data['contactNumber'],
            'password' => $data['password'],
            'status' => $this->status === true ? StatusEnum::Active->value : StatusEnum::InActive->value,
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'pincode' => $data['pincode'],
        ]);

        $defaultRoles = ['machine operator', 'designer', 'accountant'];

        foreach ($defaultRoles as $roleName) {
            $role = Role::create([
                'name' => $company->id.'_'.ucfirst($roleName),
                'guard_name' => 'company',
                'company_id' => $company->id,
            ]);

            if ($roleName === 'machine operator') {
                // $permissionName = 'company.jobcard.view';
                $permissions = ['company.jobcard.view', 'company.dashboard.view'];

                foreach ($permissions as $permissionName) {
                    // Check if the permission exists
                    $permission = SpatiePermission::where('name', $permissionName)
                        ->where('guard_name', 'company')
                        ->first();

                    // If not found, create a new permission
                    if (! $permission) {
                        $permission = SpatiePermission::create([
                            'name' => $permissionName,
                            'guard_name' => 'company',
                        ]);
                    }

                    // Assign permission to the role if not already assigned
                    DB::table('role_has_permissions')->insertOrIgnore([
                        'permission_id' => $permission->id,
                        'role_id' => $role->id,
                    ]);
                }
                // Check if the permission exists
                // $permission = SpatiePermission::where('name', $permissionName)
                //     ->where('guard_name', 'company')
                //     ->first();

                // // If not found, create a new permission
                // if (! $permission) {
                //     $permission = SpatiePermission::create([
                //         'name' => $permissionName,
                //         'guard_name' => 'company',
                //     ]);
                // }
                // DB::table('role_has_permissions')->insertOrIgnore([
                //     'permission_id' => $permission->id,
                //     'role_id' => $role->id,
                // ]);
                // Assign permission to the role
                // $role->syncPermissions([$permission]);
            }
            // permission table entry
            // role_has_permission table entery
        }

        if ($this->companyLogo) {
            $company->addMedia($this->companyLogo)->toMediaCollection('company_logo');
        }
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'max:30'],

            'email' => ['required', 'email:filter', 'max:100', Rule::unique(Staff::class, 'email')->where('type', 'admin')->ignore($this->id)],
            'contactNumber' => ['required', 'digits:10', Rule::unique(Staff::class, 'contact_number')->where('type', 'admin')->ignore($this->id)],
            'companyRegistorType' => ['required'],
        ];

        // Add specific validations only for create form
        if ($this->id === 0) {
            // $rules['password'] = ['required', 'max:100', Password::min(6)->mixedCase()->symbols()];
            $rules['password'] = ['required', 'max:100', 'alpha_num', 'min:6'];
            $rules['companyLogo'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg']; // Corrected syntax
        }

        return $rules;
    }

    // public function messages()
    // {
    //     return [
    //         'password.required' => 'The password is required.',
    //         'password.min' => 'The password must be at least 6 characters long.',
    //         'password.mixedCase' => 'The password must contain at least one uppercase and one lowercase letter.',
    //         'password.symbols' => 'The password must include at least one symbol.',
    //     ];
    // }
    public function setCompany(Company $company): void
    {
        $data = Company::with(['company_staff' => function ($staff) {
            $staff->where('type', 'admin');
        }])->where('id', $company->id)->first();
        $this->fill([
            'id' => $data?->company_staff?->id,
            'name' => $data['name'],
            'address' => $data['address'],
            'gstNo' => $data['gst_no'],
            'cinNumber' => $data['cin_number'],
            'companyRegistorType' => $data['company_registor_type'],
            'city' => $data['company_staff']->city,
            'state' => $data['company_staff']->state,
            'pincode' => $data['company_staff']->pincode,
            'email' => $data['company_staff']->email,
            'contactNumber' => $data['company_staff']->contact_number,
            'status' => $data['status'] === StatusEnum::Active->value,
        ]);
    }

    public function update(Company $company): void
    {
        $this->validate();

        $company = Company::with(['company_staff' => function ($q) {
            $q->where('type', CompanyTypeEnum::Admin->value);
        }])->where('id', $company->id)->first();

        $data = $this->getValues();

        $company->update([
            'name' => trim($data['name']),
            'gst_no' => $data['gstNo'],
            'address' => $data['address'],
            'status' => $data['status'],
            'cin_number' => $data['cinNumber'],
            'company_registor_type' => $data['companyRegistorType'],
        ]);

        $company->company_staff()
            ->where('type', CompanyTypeEnum::Admin->value)
            ->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'contact_number' => $data['contactNumber'],
                'password' => ! empty($data['password']) ? bcrypt($data['password']) : $company->company_staff->password,
                'status' => $data['status'],
                'address' => $data['address'],
                'city' => $data['city'],
                'state' => $data['state'],
                'pincode' => $data['pincode'],
            ]);

        if ($this->companyLogo) {
            $company->addMedia($this->companyLogo)->toMediaCollection('company_logo');
        }
    }

    protected function getValues(): array
    {
        return [
            'name' => ucfirst($this->name ?? ''),
            'email' => $this->email,
            'contactNumber' => $this->contactNumber,
            'address' => ucfirst($this->address ?? ''),
            'gstNo' => $this->gstNo,
            'cinNumber' => $this->cinNumber,
            'companyRegistorType' => $this->companyRegistorType,
            'city' => ucfirst($this->city ?? ''),
            'state' => ucfirst($this->state ?? ''),
            'pincode' => $this->pincode,
            'password' => $this->password,
            'status' => $this->status ? StatusEnum::Active->value : StatusEnum::InActive->value,
        ];
    }
}
