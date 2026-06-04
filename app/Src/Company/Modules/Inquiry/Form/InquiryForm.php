<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Inquiry\Form;

use Livewire\Form;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\InquiryJob;
use App\Models\CustomerProduct;
use App\Src\Company\Modules\Inquiry\Events\InquiryConfirmedEvent;
use App\Utility\Enums\InquiryStatusEnum;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;


class InquiryForm extends Form
{
    #[Validate]
    public $date;

    #[Validate(as: 'customer')]
    public $customer_id;

    #[Validate]
    public $status;

    public string $job_index = '';

    #[Validate(as: 'narration')]
    public $description;

    public $job_status;

    public $job_no;

    public $measurement_unit;

    public $id = 0;

    public $fitting_charge = 0;

    public $pesting_charge = 0;

    public $transportation_charge = 0;

    public $total_amount = 0;

    public $job_total_amount = 0;

    public $discount = 0;

    public $product_id;

    public $selectedProduct;

    public $width = 0;

    public $height = 0;

    public $qty = 0;

    public $sq_ft = 0;

    public $rate = 0;

    public $amount = 0;

    public $narration;

    public $product_name;

    public $oldJobImage = null;

    public $jobImage = null;

    public $selectedCustomer;

    public $cancellation_reason = null;

    #[Validate(as: 'Jobs')]
    public array $selectedJobs = [];

    public bool $isSelectedProductPiece = false;

    public bool $isEdit = false;

    public $estimateCount = 0;

    public $rounded_amount = 0;

    // public $totalPesting = 0;

    // public $totalFitting = 0;

    public $inquiry_pesting_charge = 0;

    public $inquiry_fitting_charge = 0;

    // public $totalTransportation = 0;
    public $inquiry_transportation = 0;

    protected function generateJobNumber()
    {
        return getUniqueNo('InquiryJob');
    }

    public function saveInquiry(?Inquiry $inquiry = null): void
    {
        $this->validate();
        // dd($this->selectedJobs);
        $inquiryValues = $this->getInquiryValues();
        if ($this->isEdit) {
            $inquiry->update($inquiryValues);
        } else {
            $inquiryValues['created_by'] = Auth::user()->id;
            $inquiry = Inquiry::create($inquiryValues);
        }
        $selectedJobNos = [];
        foreach ($this->selectedJobs as $job) {
            $jobNo = $job['job_no'] ?? $this->generateJobNumber();
            $selectedJobNos[] = $jobNo;
            $inquiryJob = $inquiry->inquiryJobs()->updateOrCreate(
                ['job_no' => $jobNo],
                $this->getInquiryJobValues(array_merge($job, ['job_no' => $jobNo]))
            );
            if (isset($job['image']) && !is_string($job['image'])) {
                $this->syncMedia($inquiryJob, $job['image']);
            }
            // logger()->info($inquiryJob->toArray(), ['image' => $job['image'] ?? null]);
        }
        $inquiry->inquiryJobs()->whereNotIn('job_no', $selectedJobNos)->delete();
        if (!$this->isEdit && $inquiry->status === InquiryStatusEnum::Confirmed->value) {
            InquiryConfirmedEvent::dispatch($inquiry);
        }
    }

    public function rules(): array
    {
        $rules = [
            'date' => ['required', 'date'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'status' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'selectedJobs' => ['required', 'array'],
            'selectedJobs.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'selectedJobs.*.product_name' => ['required', 'string', 'max:255'],
            'selectedJobs.*.measurement_unit' => ['required', 'string', 'max:255'],
            'selectedJobs.*.width' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'selectedJobs.*.height' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'selectedJobs.*.qty' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'selectedJobs.*.sq_ft' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'selectedJobs.*.rate' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'selectedJobs.*.amount' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'selectedJobs.*.image' => ['nullable', 'image', 'max:10240'],
            'selectedJobs.*.pesting_charge' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'selectedJobs.*.fitting_charge' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            // 'selectedJobs.*.transportation_charge' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'selectedJobs.*.narration' => ['nullable', 'string', 'max:255'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'pesting_charge' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'fitting_charge' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'transportation_charge' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'total_amount' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'job_total_amount' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'estimateCount' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'rounded_amount' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'cancellation_reason' => ['nullable', 'string', 'max:255', Rule::when(fn () => $this->status == InquiryStatusEnum::Cancelled->value, ['required'])],
        ];
        if ($this->isEdit) {
            $rules['selectedJobs.*.image'] = ['nullable', function (string $attribute, mixed $value, \Closure $fail): void {
                if ($value === null || $value === '') {
                    return;
                }
                if (is_string($value)) {
                    validator(['image' => $value], ['image' => ['url']])->validate();
                } else {
                    validator(['image' => $value], ['image' => ['image', 'max:10240']])->validate();
                }
            }];
        }
        return $rules;
    }
    public function messages(): array
    {
        return [
            'selectedJobs.required' => __('Please add at least one job'),
            'selectedJobs.*.product_id.required' => __('Product is required'),
            'selectedJobs.*.product_id.exists' => __('The selected product is invalid'),
            'selectedJobs.*.product_name.required' => __('Product name is required'),
            'selectedJobs.*.width.required' => __('Width is required'),
            'selectedJobs.*.height.required' => __('Height is required'),
            'selectedJobs.*.qty.required' => __('Qty is required'),
            'selectedJobs.*.sq_ft.required' => __('Sq.ft is required'),
            'selectedJobs.*.rate.required' => __('Rate is required'),
            'selectedJobs.*.amount.required' => __('Amount is required'),
            'selectedJobs.*.image.image' => __('The image must be an image'),
            'selectedJobs.*.image.max' => __('Please upload another image'),
            'selectedJobs.*.pesting_charge.min' => __('Pesting charge must be greater than 0'),
            'selectedJobs.*.fitting_charge.min' => __('Fitting charge must be greater than 0'),
            // 'selectedJobs.*.transportation_charge.min' => __('Transportation charge must be greater than 0'),
            'selectedJobs.*.narration.max' => __('Narration must be less than 255 characters'),
        ];
    }

    public function addJob(): void
    {
        $this->validateJob();
        $this->selectedJobs[] = [
            'image' => $this->jobImage ?? null,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'measurement_unit' => $this->isSelectedProductPiece ? 'piece' : 'feet',
            'width' => $this->width,
            'height' => $this->height,
            'qty' => $this->qty,
            'sq_ft' => $this->sq_ft,
            'rate' => $this->rate,
            'amount' => $this->amount,
            'pesting_charge' => $this->pesting_charge,
            'fitting_charge' => $this->fitting_charge,
            // 'transportation_charge' => $this->transportation_charge,
            'narration' => empty($this->narration) ? null : $this->narration,
        ];
        $this->job_total_amount = collect($this->selectedJobs)->sum('amount');
        $this->resetJobForm();
    }

    public function resetJobForm()
    {
        $this->reset('job_index', 'jobImage', 'product_id', 'product_name', 'width', 'height', 'qty', 'sq_ft', 'rate', 'amount', 'pesting_charge', 'fitting_charge', 'narration');
        $this->calculateEstimate();
    }

    public function calculateEstimate()
    {
        $estimate = (float) $this->job_total_amount
            + (float) $this->inquiry_pesting_charge
            + (float) $this->inquiry_fitting_charge
            + (float) $this->inquiry_transportation
            - (float) $this->discount;
        $this->estimateCount = round($estimate, 2);
        $this->rounded_amount = roundUp($estimate);
    }

    public function validateJob()
    {
        // $existingProductIds = collect($this->selectedJobs ?? [])
        //     ->forget($this->job_index ?? null)
        //     ->pluck('product_id')
        //     ->map(fn($id) => (int) $id)
        //     ->toArray();

        $messages = [
            'customer_id.required' => __('Customer is required'),
            'customer_id.exists' => __('The selected customer is invalid'),
            'product_id.required' => __('The product is required'),
            'product_id.exists' => __('The selected product is invalid'),
            // 'product_id.not_in' => __('This product is already added to the job list.'),
            'product_name.required' => __('Product name is required'),
            'width.required_if' => __('Width is required'),
            'height.required_if' => __('Height is required'),
            'qty.required' => __('Qty is required'),
            'sq_ft.required_if' => __('Sq.ft is required'),
            'rate.required' => __('Rate is required'),
            'amount.required' => __('Amount is required'),
            'pesting_charge.min' => __('Pesting charge must be greater than 0'),
            'fitting_charge.min' => __('Fitting charge must be greater than 0'),
            // 'transportation_charge.min' => __('Transportation charge must be greater than 0'),
            'narration.max' => __('Narration must be less than 255 characters'),
        ];
        $this->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
                // Rule::notIn($existingProductIds),
            ],
            'measurement_unit' => ['required', 'string', 'max:255'],
            'width' => ['required_if:isSelectedProductPiece,false', 'numeric', Rule::when(
                fn () => !$this->isSelectedProductPiece,
                ['gt:0'],
                ['min:0']
            ), 'max:999999.99'],
            'height' => ['required_if:isSelectedProductPiece,false', 'numeric', Rule::when(
                fn () => !$this->isSelectedProductPiece,
                ['gt:0'],
                ['min:0']
            ), 'max:999999.99'],
            'sq_ft' => ['required_if:isSelectedProductPiece,false', 'numeric', Rule::when(
                fn () => !$this->isSelectedProductPiece,
                ['gt:0'],
                ['min:0']
            ), 'max:999999.99'],
            'qty' => ['required', 'numeric', 'gt:0', 'max:999999.99'],
            'rate' => ['required', 'numeric', 'gt:0', 'max:999999.99'],
            'amount' => ['required', 'numeric', 'gt:0', 'max:999999.99'],
            'pesting_charge' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'fitting_charge' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            // 'transportation_charge' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'narration' => ['nullable', 'string', 'max:255'],
        ], $messages);
    }
    public function editJob($index)
    {
        $this->job_index = (string) $index;
        $this->product_id = $this->selectedJobs[$index]['product_id'];
        $this->product_name = $this->selectedJobs[$index]['product_name'];
        $this->measurement_unit = $this->selectedJobs[$index]['measurement_unit'];
        $this->width = $this->selectedJobs[$index]['width'];
        $this->height = $this->selectedJobs[$index]['height'];
        $this->qty = $this->selectedJobs[$index]['qty'];
        $this->sq_ft = $this->selectedJobs[$index]['sq_ft'];
        $this->rate = $this->selectedJobs[$index]['rate'];
        $this->amount = $this->selectedJobs[$index]['amount'];
        $this->jobImage = $this->selectedJobs[$index]['image'];
        $this->pesting_charge = $this->selectedJobs[$index]['pesting_charge'];
        $this->fitting_charge = $this->selectedJobs[$index]['fitting_charge'];
        // $this->transportation_charge = $this->selectedJobs[$index]['transportation_charge'];
        $this->narration = $this->selectedJobs[$index]['narration'];
    }

    public function updateJob()
    {
        $this->validateJob();
        $index = $this->job_index;
        $this->selectedJobs[$index]['image'] = $this->jobImage;
        $this->selectedJobs[$index]['product_id'] = $this->product_id;
        $this->selectedJobs[$index]['product_name'] = $this->product_name;
        $this->selectedJobs[$index]['measurement_unit'] = $this->isSelectedProductPiece ? 'piece' : 'feet';
        $this->selectedJobs[$index]['width'] = $this->width;
        $this->selectedJobs[$index]['height'] = $this->height;
        $this->selectedJobs[$index]['qty'] = $this->qty;
        $this->selectedJobs[$index]['sq_ft'] = $this->sq_ft;
        $this->selectedJobs[$index]['rate'] = $this->rate;
        $this->selectedJobs[$index]['amount'] = $this->amount;
        $this->selectedJobs[$index]['pesting_charge'] = $this->pesting_charge;
        $this->selectedJobs[$index]['fitting_charge'] = $this->fitting_charge;
        // $this->selectedJobs[$index]['transportation_charge'] = $this->transportation_charge;
        $this->selectedJobs[$index]['narration'] = empty($this->narration) ? null : $this->narration;
        $this->job_total_amount = collect($this->selectedJobs)->sum('amount');
        $this->resetJobForm();
    }

    public function deleteJob($index)
    {
        unset($this->selectedJobs[$index]);
        // reindex the array by using array_values
        $this->job_total_amount = collect($this->selectedJobs)->sum('amount');
        $this->selectedJobs = array_values($this->selectedJobs);
    }

    public function jobFormUpdated()
    {
        $width = floatval($this->width ?? 0);
        $height = floatval($this->height ?? 0);
        $qty = floatval($this->qty ?? 0);
        $rate = floatval($this->rate ?? 0);

        // Calculate sq_ft and store as numeric value (not formatted string)
        $sq_ft_total = $height * $width * $qty;
        $this->sq_ft = round($sq_ft_total, 2);

        $customerProduct = CustomerProduct::where('productId', $this->product_id)
            ->where('customer_id', $this->customer_id)
            ->where('company_id', Auth::user()->company_id)
            ->select('customer_min_amount')
            ->first();

        // Fetch product min price
        $product_min_price = Product::find($this->product_id);
        if (
            $customerProduct &&
            $customerProduct->customer_min_amount !== null && $customerProduct->customer_min_amount > 0
        ) {
            $min_price = floatval($customerProduct->customer_min_amount);
        } else {
            $min_price = floatval($product_min_price->minimum_price ?? 0);
        }

        // Calculate total amount
        if ($this->isSelectedProductPiece) {
            $amounttotal = $this->qty * $rate;
        } else {
            $amounttotal = $this->sq_ft * $rate;
        }

        // Ensure amount does not go below the minimum price
        $amount = round(max($amounttotal, $min_price));
        $this->amount = round($amount + $this->pesting_charge + $this->fitting_charge);
        $this->total_amount = $this->amount;
    }
    public function updateProductData($product_id)
    {
        $this->product_id = $product_id;

        $this->selectedProduct = Product::find($product_id);

        $Rate = 0;

        $productPrice = CustomerProduct::where('productId', $this->product_id)
            ->where('customer_id', $this->customer_id)
            ->where('company_id', Auth::user()->company_id)
            ->first();
        if ($productPrice) {
            $Rate = $productPrice->product_price_new ?? 0;
        } elseif ($this->selectedProduct) {
            $Rate = $this->selectedProduct->price ?? 0;
        }
        // dd($this->selectedProduct);
        $this->isSelectedProductPiece = (($this->selectedProduct->unit_name ?? null) == 'Piece');

        if ($this->selectedProduct) {
            if ($this->isSelectedProductPiece) {
                $this->width = 0;
                $this->height = 0;
                $this->pesting_charge = 0;
                $this->fitting_charge = 0;
            }
            // $this->form->qty = 0;
            // $this->form->sq_ft = 0;
            // $this->form->amount = 0;
            $this->rate = $Rate;
            $this->product_name = $this->selectedProduct->name ?? '';
        } else {
            $this->width = 0;
            $this->height = 0;
            $this->qty = 0;
            $this->sq_ft = 0;
            $this->amount = 0;
            $this->rate = 00.00;
        }
        $this->jobFormUpdated();
    }
    protected function getInquiryValues(): array
    {
        return [
            'company_id' => Auth::user()->company_id,
            'customer_id' => $this->customer_id,
            'date' => $this->date,
            'description' => $this->description,
            'pesting_charge' => $this->inquiry_pesting_charge,
            'fitting_charge' => $this->inquiry_fitting_charge,
            'transportation_charge' => $this->inquiry_transportation,
            'discount' => $this->discount,
            'total_amount' => $this->job_total_amount,
            'estimated_amount' => $this->estimateCount,
            'rounded_amount' => $this->rounded_amount,
            'status' => $this->status,
            'cancellation_reason' => $this->cancellation_reason,
        ];
    }
    public function updateCustomerData($customer_id)
    {
        $this->customer_id = $customer_id;
        $this->updateProductData($this->product_id);
    }

    protected function getInquiryJobValues(array $job): array
    {
        return [
            'job_no' => $job['job_no'] ?? $this->generateJobNumber(),
            'product_id' => $job['product_id'],
            'product_name' => $job['product_name'] ?? null,
            'measurement_unit' => $job['measurement_unit'] ?? null,
            'width' => $job['width'] ?? null,
            'height' => $job['height'] ?? null,
            'qty' => $job['qty'] ?? null,
            'sq_ft' => $job['sq_ft'] ?? null,
            'rate' => $job['rate'] ?? null,
            'amount' => $job['amount'] ?? null,
            'pesting_charge' => $job['pesting_charge'] ?? null,
            'fitting_charge' => $job['fitting_charge'] ?? null,
            // 'transportation_charge' => $job['transportation_charge'] ?? null,
            'narration' => $job['narration'] ?? null,
        ];
    }

    protected function syncMedia(InquiryJob $job, $image): void
    {
        if ($image) {
            $job->addMedia($image)->toMediaCollection('inquiry_job_image');
        }
    }
}
