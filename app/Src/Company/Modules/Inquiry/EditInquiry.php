<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Inquiry;

use App\Models\Product;
use App\Models\Inquiry;
use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Utility\Enums\JobCardStatusTypeEnum;
use App\Src\Company\Modules\Inquiry\Component\BaseInquiryComponent;
use App\Utility\Enums\InquiryStatusEnum;

class EditInquiry extends BaseInquiryComponent
{
    public $canEdit;

    public Inquiry $inquiry;

    protected string $moduleUniqueName = 'company.inquiry';

    public function mount(Inquiry $inquiry): void
    {
        if ($inquiry->status != InquiryStatusEnum::Open->value) {
            abort(403);
        }
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->form->measurement_unit = 'inch';
        $this->form->status = $this->inquiry->status;
        $this->form->cancellation_reason = $this->inquiry->cancellation_reason;
        $this->inquiry = $inquiry;
        $this->setValues();
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }
    public function setValues(): void
    {
        $this->form->date = $this->inquiry->date;
        $this->form->customer_id = $this->inquiry->customer_id;
        $this->form->description = $this->inquiry->description;
        $this->form->inquiry_pesting_charge = $this->inquiry->pesting_charge;
        $this->form->inquiry_fitting_charge = $this->inquiry->fitting_charge;
        // $this->form->transportation_charge = $this->inquiry->transportation_charge;
        $this->form->inquiry_transportation = $this->inquiry->transportation_charge;
        $this->form->discount = $this->inquiry->discount;
        $this->form->job_total_amount = $this->inquiry->total_amount;
        $this->form->estimateCount = $this->inquiry->estimated_amount;
        $this->form->rounded_amount = $this->inquiry->rounded_amount;
        $this->form->measurement_unit = 'inch';
        $this->form->isEdit = true;
        $this->form->selectedJobs = $this->inquiry->inquiryJobs->map(function ($job) {
            return [
                'job_no' => $job->job_no,
                'image' => $job->getFirstMediaUrl('inquiry_job_image'),
                'product_id' => $job->product_id,
                'product_name' => $job->product_name,
                'measurement_unit' => $job->measurement_unit,
                'width' => $job->width,
                'height' => $job->height,
                'qty' => $job->qty,
                'sq_ft' => $job->sq_ft,
                'rate' => $job->rate,
                'amount' => $job->amount,
                'pesting_charge' => $job->pesting_charge,
                'fitting_charge' => $job->fitting_charge,
                // 'transportation_charge' => $job->transportation_charge,
                'narration' => $job->narration,
            ];
        })->toArray();
    }

    public function save(): void
    {
        $this->form->saveInquiry($this->inquiry);
        flashAlert(__('app.panel.update', ['name' => __('company.inquiry')]), 'success');
        $this->redirectRoute('company.inquiry.index');
    }

    public function getStatuses(): array
    {
        return InquiryStatusEnum::cases();
    }

    public function render(): View
    {
        $title = __('app.panel.edit_name', ['name' => __('company.inquiry')]);

        $getCustomers = Customer::where('company_id', Auth::user()->company_id)->get();
        $getProducts = Product::where('company_id', Auth::user()->company_id)->get();

        if ($this->canEdit) {
            return view('company::Inquiry.views.form', [
                'title' => $title,
                'customer' => $getCustomers,
                'product' => $getProducts,
                'isEdit' => $this->form->isEdit,
                'existingLogo' => '',
                'company_id' => Auth::user()->company_id,
                'joballstatus' => JobCardStatusTypeEnum::cases(),
                'selectedJobs' => collect($this->form->selectedJobs ?? []),
            ])->layout(
                'panel::layout.app',
                [
                    'breadcrumb' => [
                        [__('company.inquiry'), route('company.inquiry.index')],
                        [__('app.panel.edit'), route('company.inquiry.edit', $this->inquiry->id)],
                    ],
                    'title' => $title,
                ]
            );
        } else {
            abort(403);
        }
    }
}
