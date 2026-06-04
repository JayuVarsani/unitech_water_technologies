<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Inquiry;

use App\Models\Product;
use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Utility\Enums\InquiryStatusEnum;
use App\Utility\Enums\JobCardStatusTypeEnum;
use App\Src\Company\Modules\Inquiry\Component\BaseInquiryComponent;

class CreateInquiry extends BaseInquiryComponent
{
    public $canView;

    public $canCreate;

    public $canEdit;

    public $canDelete;

    protected string $moduleUniqueName = 'company.inquiry';

    public function mount(): void
    {
        $this->canView = $this->hasPermission(type: 'view');
        $this->canCreate = $this->hasPermission(type: 'create');
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->canDelete = $this->hasPermission(type: 'delete');
        $this->form->status = InquiryStatusEnum::Open->value;
        $this->form->date = now()->toDateString();
        $this->form->measurement_unit = $this->form->measurement_unit ?? 'inch';
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->saveInquiry();
        flashAlert(__('app.panel.store', ['name' => __('company.inquiry')]), 'success');
        $this->redirect(route('company.inquiry.index'));
    }

    public function getStatuses(): array
    {
        return collect(InquiryStatusEnum::cases())->filter(function ($status) {
            return $status->value !== InquiryStatusEnum::Cancelled->value;
        })->values()->toArray();
    }

    public function render(): View
    {
        $title = __('app.panel.create_name', ['name' => __('company.inquiry')]);

        $getCustomers = Customer::where('company_id', Auth::user()->company_id)->get();
        $getProducts = Product::where('company_id', Auth::user()->company_id)->get();

        if ($this->canCreate) {
            return view('company::Inquiry.views.form', [
                'title' => $title,
                'customer' => $getCustomers,
                'product' => $getProducts,
                'isEdit' => false,
                'existingLogo' => '',
                'company_id' => Auth::user()->company_id,
                'joballstatus' => JobCardStatusTypeEnum::cases(),
                'selectedJobs' => collect($this->form->selectedJobs ?? []),
            ])->layout(
                'panel::layout.app',
                [
                    'breadcrumb' => [
                        [__('company.inquiry'), route('company.inquiry.index')],
                        [__('app.panel.create'), route('company.inquiry.create')],
                    ],
                    'title' => $title,
                ]
            );
        } else {
            abort(403);
        }
    }
}
