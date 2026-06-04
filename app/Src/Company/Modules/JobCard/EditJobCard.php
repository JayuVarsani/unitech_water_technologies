<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\JobCard;

use App\Models\Customer;
use App\Models\CustomerProduct;
use App\Models\JobCard;
use App\Models\JobWastage;
use App\Models\Material;
use App\Models\Product;
use App\Src\Company\Modules\JobCard\Form\JobCardForm;
use App\Utility\Enums\JobCardStatusTypeEnum;
use App\Utility\Traits\FormSessionTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class EditJobCard extends Component
{
    use FormSessionTrait;
    use WithFileUploads;
    use AuthorizesRequests;

    public $formSessionName = 'jobcardedit';

    #[Locked]
    public JobCard $jobcard;

    public JobCardForm $form;

    public $canEdit;

    public $product_id;

    public $job_status;

    public $selectedProduct;

    public $selectedCustomer;

    public $total_amount;

    public $productData = [];

    public $isFittingChecked = false;

    public bool $isSelectedProductPiece = false;

    protected ?string $moduleUniqueName = 'company.jobcard';

    protected ?string $moduleUniqueNameCustomer = 'company.customer';

    public $canCreateCustomer;

    public function mount(JobCard $jobcard): void
    {
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->jobcard = $jobcard;
        $this->form->setJobcard($jobcard);
        $this->form->total_amount = $jobcard->amount ?? 0;
        $this->form->pesting_charge = $jobcard->pesting_charge ?? 0;
        $this->form->fitting_charge = $jobcard->fitting_charge ?? 0;
        $this->form->framing_charge = $jobcard->framing_charge ?? 0;
        $this->form->transportation_charge = $jobcard->transportation_charge ?? 0;
        $this->isFittingChecked = $this->form->fitting_charge > 0;
        $this->canCreateCustomer = $this->hasPermissionCustomer(type: 'create');
    }

    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
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

    public function saveAndRedirect(): void
    {
        $this->save(); // Save job card logic.
        flashAlert(__('app.panel.update', ['name' => __('company.jobcard')]), 'success');
        $this->redirectRoute('company.jobcard.index'); // Redirect after saving.
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

        $Rate = 0;

        $productPrice = CustomerProduct::where('productId', $this->form->product_id)
            ->where('customer_id', $this->form->customer_id)
            ->where('company_id', Auth::user()->company_id)
            ->first();
        if ($productPrice) {
            $Rate = $productPrice->product_price_new ?? 0;
        } elseif ($this->selectedProduct) {
            // Otherwise, fall back to the product's default price
            $Rate = $this->selectedProduct->price ?? 0;
        }

        if ($this->selectedProduct) {
            $this->form->width = $this->form->width;
            $this->form->height = $this->form->height;
            $this->form->qty = $this->form->qty;
            $this->form->sq_ft = $this->form->sq_ft;
            $this->form->amount = $this->form->amount;
            $this->form->rate = $Rate ?? 0;
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
        $this->updated('form.rate');
    }

    public function updatedForm($key, $value)
    {
        if (in_array($key, ['form.fitting_charge', 'form.pesting_charge', 'form.framing_charge', 'form.transportaion_charge'])) {
            $this->form[$key] = $value ? '10.00' : '0';
        }
    }

    public function updatedIsFittingChecked($value)
    {
        // Reset fitting_charge if checkbox is unchecked
        if (! $value) {
            $this->form->fitting_charge = 0;
        }
    }

    public function updateCustomerData($customer_id)
    {

        $this->form->customer_id = $customer_id;

        $this->selectedCustomer = JobCard::where('customer_id', $customer_id)
            ->where('company_id', Auth::user()->company_id)
            ->where('status', 1)
            ->where('job_status', JobCardStatusTypeEnum::Pending->value)
            ->get();

        return $this->selectedCustomer;
    }

    //

    // public function updated($propertyName)
    // {
    //     if (in_array($propertyName, ['form.qty', 'form.sq_ft', 'form.rate', 'form.height', 'form.width'])) {
    //         $this->form->amount = ($this->form->qty ?? 0) * ($this->form->sq_ft ?? 0) * ($this->form->rate ?? 0);
    //         $this->form->total_amount = ($this->form->qty ?? 0) * ($this->form->sq_ft ?? 0) * ($this->form->rate ?? 0);
    //         $this->form->sq_ft = ($this->form->height ?? 0) * ($this->form->width ?? 0);
    //     }
    // }
    // public function updated($propertyName)
    // {
    //     if (in_array($propertyName, ['form.width', 'form.height', 'form.qty', 'form.rate'])) {
    //         // Ensure the sq_ft value is updated first
    //         // $this->form->sq_ft = ($this->form->height ?? 0) * ($this->form->width ?? 0) * ($this->form->qty ?? 0);
    //         $sq_ft_total = ($this->form->height ?? 0) * ($this->form->width ?? 0) * ($this->form->qty ?? 0);
    //         $this->form->sq_ft = number_format($sq_ft_total, 2);
    //         // Fetch product min price
    //         $product_min_price = Product::find($this->form->product_id);
    //         $min_price = $product_min_price->minimum_price ?? 0;

    //         // Calculate total amount
    //         $amounttotal = ($this->form->sq_ft ?? 0) * ($this->form->rate ?? 0);

    //         // Ensure amount does not go below the minimum price
    //         $this->form->amount = max($amounttotal, $min_price);
    //         $this->form->total_amount = $this->form->amount;
    //     }
    // }
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
        $this->dispatch('$refresh');
    }

    public function render(): View
    {

        $title = __('app.panel.edit_name', ['name' => __('company.jobcard')]);
        $getCustomers = Customer::where('company_id', Auth::user()->company_id)->get();
        $getProducts = Product::where('company_id', Auth::user()->company_id)->get();
        $selectedProduct = JobCard::where('id', $this->jobcard->id)->first();
        $selectedCustomer = JobCard::where('customer_id', $this->form->customer_id)
            ->where('company_id', Auth::user()->company_id)
            ->where('status', 1)
            ->where('job_status', JobCardStatusTypeEnum::Pending->value)->get();

        $wastage_jobs_whole = JobWastage::with('material')->where('job_id', $this->jobcard->id)->orderBy('id', 'desc')->get();

        // $materials = [];
        // if ($wastage_jobs->isNotEmpty()) {

        //     // $materialIds = $wastage_jobs->pluck('material_id')->toArray();
        //     // $materials = Material::whereIn('id', $materialIds)->pluck('material_name')->toArray();
        //     $materials = $wastage_jobs->map(function ($wastage) {
        //         return "{$wastage->material->material_name} (h={$wastage->height}, w={$wastage->weight})";
        //     })->toArray();
        // }
        // $displayMaterials = implode(' + ', $materials);

        $wastage_jobs = JobWastage::where('job_id', $this->jobcard->id)
            ->with('material') // Eager load the related material
            ->get();

        $materials = [];

        if ($wastage_jobs->isNotEmpty()) {
            $materials = $wastage_jobs->map(function ($wastage) {
                return isset($wastage->material)
                    ? "{$wastage->material->material_name} (h={$wastage->height}, w={$wastage->width})"
                    : null;
            })->filter()->toArray(); // Filter out any null values
        }
        if ($this->canEdit) {
            return view('company::JobCard.views.form', [
                'title' => $title,
                'isEdit' => 'true',
                'customer' => $getCustomers,
                'product' => $getProducts,
                'selectedProduct' => $selectedProduct,
                'selectedCustomer' => $selectedCustomer,
                'existingLogo' => $this->jobcard->getfirstMediaUrl('job_image'),
                'company_id' => Auth::user()->company_id,
                'joballstatus' => JobCardStatusTypeEnum::cases(),
                'wastage_jobs' => $wastage_jobs,
                'materials' => $materials,
                'wastage_jobs_whole' => $wastage_jobs_whole,

            ])
                ->layout('panel::layout.app', [
                    'breadcrumb' => [
                        ['Job Card', route('company.jobcard.index')],
                        [__('app.panel.edit'), route('company.jobcard.edit', $this->jobcard->id)],
                    ],
                    'title' => $title,
                ]);
        } else {
            abort(403);
        }
    }
}
