<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\CustomerManagement\CustomerGroup\Form;

use App\Models\CustomerGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CustomerGroupForm extends Form
{
    #[Validate]
    public $id = 0;

    #[Validate]
    public $name;

    public function createCustomerGroup(): void
    {
        $this->validate();
        CustomerGroup::create($this->getValues());
    }

    public function rules(): array
    {
        // logger('Edit group ID:', ['id' => $this->id]);

        return [
            'name' => ['required', 'max:30', Rule::unique(CustomerGroup::class, 'name')
                ->where(fn ($query) => $query->where('company_id', Auth::user()->company_id))
                ->ignore($this->id, 'id')],
        ];
    }

    public function setCustomerGroup(CustomerGroup $customerGroup): void
    {
        $this->id = $customerGroup->id;
        $this->fill([
            'name' => $customerGroup->name,
        ]);
    }

    public function update(CustomerGroup $customerGroup): void
    {
        $this->validate();
        $customerGroup->update($this->getValues());
    }

    protected function getValues(): array
    {
        return [
            'company_id' => Auth::user()->company_id,
            'name' => ucfirst($this->name ?? ''),
        ];
    }
}
