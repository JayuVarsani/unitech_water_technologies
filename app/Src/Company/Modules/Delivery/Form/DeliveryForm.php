<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Delivery\Form;

use App\Models\Customer;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Form;

class DeliveryForm extends Form
{
    public $entryType = 'form';

    public $customerId = '';

    public $deliveryDate = '';

    public $itemDetails = '';

    public $finalAmount = '';

    public $customerSignature = '';

    public $deliveryImage;

    public $oldDeliveryImage = null;

    public $id = 0;

    public function createDelivery(): Delivery
    {
        $this->validate();

        $delivery = Delivery::create($this->getValues());
        $this->syncMedia($delivery);

        return $delivery;
    }

    public function rules(): array
    {
        $rules = [
            'entryType' => ['required', Rule::in(['form', 'image'])],
            'customerId' => [
                'required',
                Rule::exists('customers', 'id')->where(
                    fn ($query) => $query->where('company_id', Auth::user()->company_id)
                ),
            ],
            'deliveryDate' => ['required', 'date'],
            'finalAmount' => ['required', 'numeric', 'min:0', 'max:999999999.99'],
        ];

        if ($this->entryType === 'form') {
            $rules['itemDetails'] = ['required', 'string', 'max:5000'];
            $rules['customerSignature'] = ['required', 'string'];
        } else {
            if (! $this->oldDeliveryImage && ! $this->deliveryImage) {
                $rules['deliveryImage'] = ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:1048576'];
            } elseif ($this->deliveryImage) {
                $rules['deliveryImage'] = ['file', 'mimes:jpg,jpeg,png,gif,webp', 'max:1048576'];
            }
        }

        return $rules;
    }

    public function validationAttributes(): array
    {
        return [
            'customerId' => 'customer',
            'deliveryDate' => 'delivery date',
            'itemDetails' => 'item details',
            'finalAmount' => 'final amount',
            'customerSignature' => 'customer signature',
            'deliveryImage' => 'delivery challan image',
        ];
    }

    public function setEntryType(string $type): void
    {
        if (! in_array($type, ['form', 'image'], true)) {
            return;
        }

        if ($type === 'image') {
            $this->itemDetails = '';
            $this->customerSignature = '';
        } else {
            $this->deliveryImage = null;
        }

        $this->entryType = $type;
    }

    public function setDelivery(Delivery $delivery): void
    {
        $this->id = $delivery->id;

        $this->fill([
            'entryType' => $delivery->entry_type ?? 'form',
            'customerId' => $delivery->customer_id ?? '',
            'deliveryDate' => $delivery->delivery_date?->format('Y-m-d') ?? '',
            'itemDetails' => $delivery->item_details ?? '',
            'finalAmount' => $delivery->final_amount ?? '',
            'customerSignature' => $delivery->customer_signature ?? '',
            'oldDeliveryImage' => $delivery->getFirstMediaUrl('delivery_challan') ?: null,
        ]);
    }

    public function update(Delivery $delivery): void
    {
        $this->validate();
        $delivery->update($this->getValues());

        if ($this->entryType === 'form') {
            $delivery->clearMediaCollection('delivery_challan');
        } else {
            $this->syncMedia($delivery);
        }
    }

    protected function getValues(): array
    {
        $customer = Customer::where('id', $this->customerId)
            ->where('company_id', Auth::user()->company_id)
            ->firstOrFail();

        $values = [
            'company_id' => Auth::user()->company_id,
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'entry_type' => $this->entryType,
            'delivery_date' => $this->deliveryDate,
            'final_amount' => $this->finalAmount,
        ];

        if ($this->entryType === 'form') {
            $values['item_details'] = $this->itemDetails;
            $values['customer_signature'] = $this->customerSignature;
        } else {
            $values['item_details'] = null;
            $values['customer_signature'] = null;
        }

        return $values;
    }

    protected function syncMedia(Delivery $delivery): void
    {
        if ($this->entryType !== 'image' || ! $this->deliveryImage) {
            return;
        }

        $delivery->clearMediaCollection('delivery_challan');
        $delivery->addMedia($this->deliveryImage)->toMediaCollection('delivery_challan');
    }
}
