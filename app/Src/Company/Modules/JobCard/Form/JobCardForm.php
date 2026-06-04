<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\JobCard\Form;

use App\Models\JobCard;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Spatie\Image\Image;

class JobCardForm extends Form
{
    #[Validate]
    public $job_date;

    #[Validate(as: 'customer')]
    public $customer_id;

    #[Validate(as: 'product')]
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

    #[Validate(as: 'image') ]
    public $jobImage;

    public $is_inch;

    public $product_name;

    public $total_amount = 0;

    public $fitting_charge = 0;

    public $pesting_charge = 0;

    public $framing_charge = 0;

    public $transportation_charge = 0;

    public $description;

    public $job_status;

    public $customer_id_filter;

    public $job_no;

    public $id = 0;

    public $oldJobImage = null;

    public ?string $button_status = null;
    // protected function syncMedia(JobCard $jobcard): void
    // {

    //     // Validate the image if provided
    //     if ($this->jobImage) {
    //         // Validation logic (example: file size, type)
    //         $this->validate([
    //             'jobImage' => 'required', // Adjust validation rules as needed
    //         ]);

    //         $jobcard->addMedia($this->jobImage)->toMediaCollection('job_image'); // Add new media
    //     } else {
    //         // If no image provided for creation, optionally handle default behavior
    //         if (!$jobcard->exists) {
    //             throw ValidationException::withMessages(['jobImage' => 'An image is required for creating a Job Card.']);
    //         }
    //     }
    // }
    // private function generateJobNumber(): string
    // {

    //     $latestJobNo = JobCard::whereRaw("job_no REGEXP '^[A-Z][0-9]+$'")
    //         ->orderByRaw('CAST(SUBSTRING(job_no, 2) AS UNSIGNED) DESC') // Sort by numeric part
    //         ->value('job_no'); // Ensure to extract only the 'job_no' column as a string

    //     if ($latestJobNo) {
    //         // Extract the letter and number parts from the job number
    //         $letter = $latestJobNo[0];
    //         $number = (int) substr($latestJobNo, 1); // Substring from index 1 to extract the number part

    //         // Increment the number or roll over to the next letter
    //         if ($number < 9999) {
    //             $number++;
    //         } else {
    //             // Move to the next letter (e.g., A to B)
    //             $letter = chr(ord($letter) + 1);
    //             $number = 1;
    //         }
    //     } else {
    //         // Default to A1 for the first job number if none found
    //         $letter = 'A';
    //         $number = 1;
    //     }

    //     // Return the new job number as a string (without padding)
    //     return $letter.$number;
    // }

    public static function generateJobNumber($companyId)
    {
        // Get the last job for this company
        $lastJob = JobCard::where('company_id', $companyId)
            ->orderBy('id', 'desc')
            ->first();

        // Get next job number
        $nextNumber = $lastJob ? ((int) substr($lastJob->job_no, 1) + 1) : 1;

        // Generate job number with "A" prefix for all companies
        return 'A'.$nextNumber;
    }

    public function createJobcard(): JobCard
    {

        $this->validate();

        $jobdata = JobCard::create($this->getValues());
        // $job_no = 'A'.$jobdata->id; // Generate the job_no like A.primaryid
        // $jobdata->update(['job_no' => $job_no]);

        $this->syncMedia($jobdata);
        $this->reset();
        $this->reset(['jobImage']);

        return $jobdata;
    }

    public function rules(): array
    {
        $rules = [
            'job_date' => ['required', 'date'],
            'customer_id' => ['required', 'integer'],
            'product_id' => ['required', 'integer'],
            'width' => ['required', 'numeric', 'min:0'],
            'height' => ['required', 'numeric', 'min:0'],
            'qty' => ['required', 'numeric', 'min:0'],
            'sq_ft' => ['required', 'numeric', 'min:0'],
            'rate' => ['required', 'numeric', 'min:0'],
            'amount' => ['required', 'numeric', 'min:1'],
            'total_amount' => ['required', 'numeric', 'min:1'],
            'is_inch' => ['required', 'boolean'],
            // 'jobImage' => ['nullable'],
        ];

        if ($this->jobImage && $this->id > 0) {
            $rules['jobImage'] = ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:1048576'];
        }

        return $rules;

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
            'job_status' => $jobcard->job_status,
            'job_no' => $jobcard->job_no,
            'is_inch' => $jobcard->is_inch,
            'oldJobImage' => $jobcard->getFirstMediaUrl('job_image'),
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

        $job_no = $this->id === 0 ? $this->generateJobNumber(Auth::user()->company_id) : $this->job_no;

        return [
            'job_no' => $job_no,
            'job_date' => $this->job_date,
            'customer_id' => $this->customer_id,
            'product_id' => $this->product_id,
            'product_name' => $product->name,
            'description' => ucfirst($this->description ?? ''),
            'is_inch' => $this->is_inch ?? '0',
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
            'job_status' => $this->job_status ?? 'pending',
            'company_id' => Auth::user()->company_id,
            'final_total' => ($this->amount) + ($this->framing_charge) + ($this->fitting_charge) + ($this->pesting_charge) + ($this->transportation_charge),
        ];
    }

    protected function syncMedia(JobCard $jobcard): void
    {

        if ($this->jobImage) {

            $jobcard->addMedia($this->jobImage)->toMediaCollection('job_image');
        }

    }
}
