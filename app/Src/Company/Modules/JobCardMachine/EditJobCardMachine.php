<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\JobCardMachine;

use App\Models\Customer;
use App\Models\JobCard;
use App\Models\Product;
use App\Src\Company\Modules\JobCardMachine\Form\JobCardMachineForm;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditJobCardMachine extends Component
{
    use WithFileUploads;

    #[Locked]
    public JobCard $jobcard;

    public JobCardMachineForm $form;

    public $canEdit;

    public $product_id;

    public $job_status;

    public $selectedProduct;

    public $selectedCustomer;

    public $total_amount;

    public $productData = [];

    protected ?string $moduleUniqueName = 'company.jobcard-machine';

    public function mount(JobCard $jobcard): void
    {
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->jobcard = $jobcard;
        $this->form->setJobcard($jobcard);

        $this->form->pesting_charge = $jobcard->pesting_charge ?? 0;
        $this->form->fitting_charge = $jobcard->fitting_charge ?? 0;
        $this->form->framing_charge = $jobcard->framing_charge ?? 0;

    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->update($this->jobcard);
        flashAlert(__('app.panel.update', ['name' => __('company.jobcard')]), 'success');
        $this->redirectRoute('company.jobcard.index');
    }

    public function updateProductData($product_id)
    {

        $this->form->product_id = $product_id;
        $this->selectedProduct = Product::find($product_id);

        if ($this->selectedProduct) {
            $this->form->width = $this->form->width;
            $this->form->height = $this->form->height;
            $this->form->qty = $this->form->qty;
            $this->form->sq_ft = $this->form->sq_ft;
            $this->form->amount = $this->form->amount;
            $this->form->rate = $this->selectedProduct->price ?? 0;
            $this->form->product_name = $this->selectedProduct->name ?? '';
        } else {

            $this->form->width = 0;
            $this->form->height = 0;
            $this->form->qty = 0;
            $this->form->sq_ft = 0;
            $this->form->amount = 0;
            $this->form->rate = 00.00;
        }
    }

    public function updateCustomerData($customer_id)
    {

        $this->form->customer_id = $customer_id;

        $this->selectedCustomer = JobCard::where('customer_id', $customer_id)
            ->where('company_id', Auth::user()->company_id)
            ->where('job_status', 'Pending')->get();

        return $this->selectedCustomer;

    }

    public function render(): View
    {

        $title = __('app.panel.edit_name', ['name' => __('company.jobcard')]);
        $getCustomers = Customer::all();
        $getProducts = Product::all();
        $selectedProduct = JobCard::where('id', $this->jobcard->id)->first();
        $selectedCustomer = JobCard::where('customer_id', $this->form->customer_id)
            ->where('company_id', Auth::user()->company_id)
            ->where('job_status', 'Pending')->get();

        if ($this->canEdit) {
            return view('company::jobcard.views.form', [
                'title' => $title,
                'customer' => $getCustomers,
                'product' => $getProducts,
                'selectedProduct' => $selectedProduct,
                'selectedCustomer' => $selectedCustomer,

            ])
                ->layout('panel::layout.app', [
                    'breadcrumb' => [
                        [str()->plural(__('company.jobcard')), route('company.jobcard.index')],
                        [__('app.panel.edit'), route('company.jobcard.edit', $this->jobcard->id)],
                    ],
                    'title' => $title,
                ]);
        } else {
            abort(403);
        }
    }
}
