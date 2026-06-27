<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\CustomerManagement\Customer\Form;

use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CustomerForm extends Form
{
    // #[Validate]
    public $id = 0;

    #[Validate]
    public $name;

    #[Validate]
    public $email;

    #[Validate (as: 'mobile number')]
    public $contactNumber;

    #[Validate]
    public $whatsappNumber;

    #[Validate]
    public $address;

    #[Validate]
    public $city;

    #[Validate]
    public $state;

    #[Validate]
    public $pincode;

    #[Validate]
    public $area;

    #[Validate]
    public $customerGroup;

    // #[Validate]
    // public $customerRegisterType;

    #[Validate]
    public $openingBalance;

    public $gstNo;

    public $autoReminder;

    // #[Validate]
    public $productId;

    // #[Validate]
    public $product_price_new;

    // #[Validate]
    public $product_price;

    public $selectedproducts = [];

    // #[Validate]
    public $customer_min_amount;

    // #[Validate]
    public $product_min_amount;

    public function createCustomer(): Customer
    {
        $this->validate();
        $customer = Customer::create($this->getValues());

        return $customer;
    }

    public function fillFromArray(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function rules(): array
    {
        $this->resetErrorBag();
        return [
            'name' => ['required', 'max:30'],
            // 'email' => ['nullable', 'email:filter', 'max:100', Rule::unique(Customer::class, 'email')->ignore($this->id)],
            'email' => ['nullable', 'email'],
            'contactNumber' => ['required', 'numeric','digits_between:8,12', 'gt:0', Rule::unique(Customer::class, 'contact_number')->ignore($this->id)],
            'whatsappNumber' => ['required', 'numeric','digits_between:8,12', 'gt:0'],
            'address' => ['required', 'max:300'],
            'city' => ['nullable', 'max:30'],
            'state' => ['nullable', 'max:30'],
            'pincode' => ['nullable', 'numeric'],
            'area' => ['nullable', 'max:30'],
            'customerGroup' => ['required'],
            // 'customerRegisterType' => ['required'],
            'openingBalance' => ['required', 'numeric', 'min:0', 'max:999999'],
            // 'productId' => ['required'],
            // 'product_price_new' => ['required'],
            // 'product_price' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            // 'contactNumber.regex' => 'The mobile number must be 8 to 12 digits only.',
            // 'whatsappNumber.regex' => 'The WhatsApp number must be 8 to 12 digits only.',
            'contactNumber.unique' => 'This mobile number is already used by another customer.',
        ];
    }

    public function setCustomer(Customer $customer): void
    {
        $this->id = $customer->id;

        $this->fill([
            'name' => $customer->name,
            'email' => $customer->email,
            'contactNumber' => $customer->contact_number,
            'whatsappNumber' => $customer->whatsapp_number,
            'address' => $customer->address,
            'city' => $customer->city,
            'state' => $customer->state,
            'pincode' => $customer->pincode,
            'area' => $customer->area,
            'customerGroup' => $customer->customer_group_id,
            // 'customerRegisterType' => $customer->customer_register_type,
            'gstNo' => $customer->gst_no,
            'autoReminder' => $customer->auto_reminder,
            'openingBalance' => $customer->opening_balance,
            'selectedProducts' => $customer->products->pluck('id')->toArray(),
        ]);
    }

    public function update(Customer $customer): void
    {
        $this->validate();

        $customer->update($this->getValues());
    }

    protected function getValues(): array
    {
        $customerGroup = CustomerGroup::where('id', $this->customerGroup)->first();

        return [
            'company_id' => Auth::user()->company_id,
            'name' => ucfirst(trim((string) ($this->name ?? ''))),
            'email' => $this->email,
            'contact_number' => $this->contactNumber,
            'whatsapp_number' => $this->whatsappNumber,
            'address' => ucfirst(trim((string) ($this->address ?? ''))),
            'city' => ucfirst(trim((string) ($this->city ?? ''))),
            'state' => ucfirst(trim((string) ($this->state ?? ''))),
            'pincode' => $this->pincode,
            'area' => ucfirst(trim((string) ($this->area ?? ''))),
            'customer_group_id' => $this->customerGroup,
            'customer_group_name' => $customerGroup->name,
            // 'customer_register_type' => ucfirst($this->customerRegisterType),
            'gst_no' => ucfirst($this->gstNo ?? ''),
            'auto_reminder' => in_array($this->autoReminder, ['OnceInWeek', 'OnceInMonth'])
            ? $this->autoReminder
            : null,
            'opening_balance' => $this->openingBalance,
        ];
    }
}
