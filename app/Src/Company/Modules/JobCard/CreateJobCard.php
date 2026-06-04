<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\JobCard;

use App\Models\Customer;
use App\Models\CustomerProduct;
use App\Models\JobCard;
use App\Models\Product;
use App\Src\Company\Modules\JobCard\Form\JobCardForm;
use App\Utility\Enums\JobCardStatusTypeEnum;
use App\Utility\Traits\FormSessionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateJobCard extends Component
{
    use FormSessionTrait;
    use WithFileUploads;

    public $formSessionName = 'jobcard';

    public JobCardForm $form;

    public $canView;

    public $canCreate;

    public $canEdit;

    public $canDelete;

    public $canCreateCustomer;

    public $product_id;

    public $customer_id;

    public $job_status;

    public $button_status;

    public $selectedProduct;

    public $selectedCustomer;

    public $productData = [];

    public $isFittingChecked = false;

    public $isPestingChecked = false;

    public $isFramingChecked = false;

    public $isTransportationChecked = false;

    public $jobImage;

    public $is_inch = 0;

    public bool $isSelectedProductPiece = false;

    protected ?string $moduleUniqueName = 'company.jobcard';

    protected ?string $moduleUniqueNameCustomer = 'company.customer';

    public function mount()
    {
        $this->canCreate = $this->hasPermission(type: 'create');
        $this->canCreateCustomer = $this->hasPermissionCustomer(type: 'create');
        $this->form->job_date = now()->toDateString();
        $this->form->is_inch = $this->form->is_inch ?? '1';
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function hasPermissionCustomer(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueNameCustomer ? auth()->user()->hasPermission($this->moduleUniqueNameCustomer, $type, $abort) : true;
    }

    public function storeToSession()
    {
        $currentUrl = request()->header('referer', url()->previous());
        $this->storeFromSession();
        $this->redirectRoute('company.customer-management.customer.create', ['redirect_to' => $currentUrl]);
    }

    public function getListeners(): array
    {
        return [
            'jobCardAdded' => 'updateCustomerData',
            'jobcardDeleted' => 'refreshJobCards',
        ];
    }

    public function refreshJobCards(): void
    {
        $this->selectedCustomer = JobCard::where('customer_id', $this->form->customer_id)
            ->where('company_id', Auth::user()->company_id)
            ->where('status', '1')
            ->where('job_status', 'Pending')->get();
    }

    public function save(): void
    {
        $button_status = $this->form->button_status;

        $jobdata = $this->form->createJobcard();
        $customerName = $this->form->customer_id ?? null;
        $jobDate = $this->form->job_date ?? now()->toDateString();

        $this->form->customer_id = $customerName;
        $this->form->job_date = $jobDate;
        $this->dispatch('jobCardAdded', $jobdata->customer_id);

        $this->reset(['isFittingChecked']);
        $this->reset(['isPestingChecked']);
        $this->reset(['isFramingChecked']);
        $this->reset(['isTransportationChecked']);
        $this->dispatch('resetAlpineState');
        $this->purgeFromSession();
        $this->dispatch('scrollToTop');
        flashAlert(__('app.panel.store', ['name' => __('company.jobcard')]), 'success');
        if ($button_status === 'finish') {
            $this->redirectRoute('company.jobcard.index');
        }
    }

    public function getEstimatedAmount()
    {
        return $this->form->total_amount +
            $this->form->fitting_charge +
            $this->form->pesting_charge +
            $this->form->framing_charge +
            $this->form->transportation_charge;
    }

    public function updateProductData($product_id)
    {

        $this->form->product_id = $product_id;

        $this->selectedProduct = Product::find($product_id);

        $Rate = 0;

        $productPrice = CustomerProduct::where('productId', $this->form->product_id)
            ->where('customer_id', $this->form->customer_id)
            ->where('company_id', Auth::user()->company_id)
            ->first();
        if ($productPrice) {
            $Rate = $productPrice->product_price_new ?? 0;
        } elseif ($this->selectedProduct) {
            // Otherwise, fall back to the product's default price
            // $product_price = $this->selectedProduct->price ?? 0;
            // $product_min_price = $this->selectedProduct->minimum_price ?? 0;

            // if ($product_price > $product_min_price) {
            //     $Rate = $product_price;
            // } else {
            //     $Rate = $product_min_price;
            // }
            $Rate = $this->selectedProduct->price ?? 0;
        }

        if ($this->selectedProduct) {
            $this->form->width = 0;
            $this->form->height = 0;
            // $this->form->qty = 0;
            // $this->form->sq_ft = 0;
            // $this->form->amount = 0;
            $this->form->rate = $Rate;
            $this->form->product_name = $this->selectedProduct->name ?? '';
        } else {
            $this->form->width = 0;
            $this->form->height = 0;
            $this->form->qty = 0;
            $this->form->sq_ft = 0;
            $this->form->amount = 0;
            $this->form->rate = 00.00;
        }
        $this->isSelectedProductPiece = (($this->selectedProduct->unit_name ?? null) === 'Piece');
    }

    public function updateCustomerData($customer_id)
    {

        $this->form->customer_id = $customer_id;

        $this->selectedCustomer = JobCard::where('customer_id', $customer_id)
            ->where('company_id', Auth::user()->company_id)
            ->where('status', '1')
            ->get();

        return $this->selectedCustomer;
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['form.width', 'form.height', 'form.qty', 'form.rate', 'form.customer_id', 'form.product_id'])) {
            // Convert inputs to float to handle decimal values properly
            $width = floatval($this->form->width ?? 0);
            $height = floatval($this->form->height ?? 0);
            $qty = floatval($this->form->qty ?? 0);
            $rate = floatval($this->form->rate ?? 0);

            // Calculate sq_ft and store as numeric value (not formatted string)
            $sq_ft_total = $height * $width * $qty;
            $this->form->sq_ft = round($sq_ft_total, 2);

            $customerProduct = CustomerProduct::where('productId', $this->form->product_id)
                ->where('customer_id', $this->form->customer_id)
                ->where('company_id', Auth::user()->company_id)
                ->select('customer_min_amount')
                ->first();

            // Fetch product min price
            $product_min_price = Product::find($this->form->product_id);
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
                $amounttotal = $this->form->qty * $rate;
            } else {
                $amounttotal = $this->form->sq_ft * $rate;
            }

            // Ensure amount does not go below the minimum price
            $this->form->amount = max($amounttotal, $min_price);
            $this->form->total_amount = $this->form->amount;
        }
    }

    public function render(): View
    {

        $title = __('app.panel.create_name', ['name' => __('company.jobcard')]);

        $getCustomers = Customer::where('company_id', Auth::user()->company_id)->get();
        $getProducts = Product::where('company_id', Auth::user()->company_id)->get();

        if ($this->canCreate) {
            return view('company::JobCard.views.form', [
                'title' => $title,
                'customer' => $getCustomers,
                'product' => $getProducts,
                'isEdit' => false,
                'existingLogo' => '',
                'company_id' => Auth::user()->company_id,
                'joballstatus' => JobCardStatusTypeEnum::cases(),

            ])->layout(
                'panel::layout.app',
                [
                    'breadcrumb' => [
                        ['Job Card', route('company.jobcard.index')],
                        [__('app.panel.create'), route('company.jobcard.create')],
                    ],
                    'title' => $title,
                ]
            );
        } else {
            abort(403);
        }
    }

    public function delete(JobCard $jobcard): void
    {
        try {
            $this->authorize('delete', $jobcard);
            $jobcard->delete();
            flashAlert(__('app.panel.delete', ['name' => __('company.jobcard')]), 'success');
            $this->dispatch('jobcardDeleted', $jobcard->id);
        } catch (\Exception $e) {
            flashAlert($e->getMessage(), 'danger');
        }
    }
}
