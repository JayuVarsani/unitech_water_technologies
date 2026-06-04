<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\JobCardMachine\Form;

use App\Models\JobCard;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class JobCardMachineForm extends Form
{
    #[Validate]
    public $job_date;

    #[Validate]
    public $customer_id;

    #[Validate]
    public $product_id;

    #[Validate]
    public $width;

    #[Validate]
    public $height;

    #[Validate]
    public $qty;

    #[Validate]
    public $sq_ft;

    #[Validate]
    public $rate;

    #[Validate]
    public $amount;

    #[Validate]
    public $jobImage;

    public $product_name;

    public $total_amount = 0;

    public $fitting_charge = 0;

    public $pesting_charge = 0;

    public $framing_charge = 0;

    public $transportation_charge = 0;

    public $description;

    public $job_status;

    public $oldJobImage = null;

    public $id = 0;

    public function createJobcard(): JobCard
    {

        $this->validate();

        $jobdata = JobCard::create($this->getValues());
        $this->syncMedia($jobdata);

        return $jobdata;
    }

    public function rules(): array
    {
        return [
            'job_date' => ['required', 'date'],
            'customer_id' => ['required', 'integer'],
            'product_id' => ['required', 'integer'],
            'width' => ['required', 'numeric', 'min:0'],
            'height' => ['required', 'numeric', 'min:0'],
            'qty' => ['required', 'integer', 'min:1'],
            'sq_ft' => ['required', 'numeric', 'min:0'],
            'rate' => ['required', 'numeric', 'min:0'],
            'amount' => ['required', 'numeric', 'min:0'],

        ];
    }

    public function setJobcard(JobCard $jobcard): void
    {
        $this->id = $jobcard->id;

        $this->fill([
            'customer_id' => $jobcard->customer_id,
            'product_id' => $jobcard->product_id,
            'job_date' => $jobcard->job_date,
            'description' => $jobcard->description,
            'amount' => $jobcard->amount,
            'product_name' => $jobcard->product_name,
            'width' => $jobcard->width,
            'height' => $jobcard->height,
            'qty' => $jobcard->qty,
            'sq_ft' => $jobcard->sq_ft,
            'rate' => $jobcard->rate,
        ]);
    }

    public function update(JobCard $jobcard): void
    {
        $this->validate();
        $jobcard->update($this->getValues());
        $this->syncMedia($jobcard);

    }

    protected function getValues(): array
    {

        $product = Product::where('id', $this->product_id)->first();
        $job_no = $this->generateJobNumber();

        return [
            'job_no' => $job_no,
            'job_date' => $this->job_date,
            'customer_id' => $this->customer_id,
            'product_id' => $this->product_id,
            'product_name' => $product->name,
            'description' => $this->description,
            'width' => $this->width,
            'height' => $this->height,
            'qty' => $this->qty,
            'sq_ft' => $this->sq_ft,
            'rate' => $this->rate,
            'amount' => $this->amount,
            'fitting_charge' => $this->fitting_charge ?? '0',
            'pesting_charge' => $this->pesting_charge ?? '0',
            'framing_charge' => $this->framing_charge ?? '0',
            'transportation_charge' => $this->transportation_charge ?? '0',
            'job_status' => $this->job_status,
            'company_id' => Auth::user()->company_id,
            'final_total' => ($this->amount) + ($this->fitting_charge) + ($this->pesting_charge) + ($this->transportation_charge),
        ];
    }

    protected function syncMedia(JobCard $jobcard): void
    {
        if ($this->jobImage) {
            $jobcard->addMedia($this->jobImage)->toMediaCollection('job_image');
        }

    }

    private function generateJobNumber(): string
    {

        $latestJobNo = JobCard::where('job_no', 'LIKE', 'A%')  // Adjust the 'A%' to the starting letter, e.g., 'A%'
            ->orderBy('job_no', 'desc')
            ->value('job_no');

        if ($latestJobNo) {
            $letter = $latestJobNo[0];
            $number = (int) substr($latestJobNo, 1);
            if ($number < 9999) {
                $number++;
            } else {

                $letter = chr(ord($letter) + 1);
                $number = 1;
            }
        } else {

            $letter = 'A';
            $number = 1;
        }

        return $letter.$number;
    }
}
