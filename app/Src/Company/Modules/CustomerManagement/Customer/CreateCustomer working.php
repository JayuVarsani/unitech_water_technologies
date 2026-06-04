<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\CustomerManagement\Customer;

use App\Models\Company;
use App\Models\CustomerGroup;
use App\Models\CustomerProduct;
use App\Models\Product;
use App\Src\Company\Modules\CustomerManagement\Customer\Form\CustomerForm;
use App\Utility\Traits\FormSessionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateCustomer extends Component
{
    use FormSessionTrait;

    public $formSessionName = 'customer';

    public CustomerForm $form;

    public $canCreate;

    public string $redirectTo = '';

    public $selectedProducts = [];

    public $lastAddedProduct = null;

    public $product = [];

    public $afterdeleteProducts = [];

    protected ?string $moduleUniqueName = 'company.customer';

    // public function updateDefaultPrice($productId)
    // {

    //     $this->form->productId = $productId;

    //     $product = Product::find($productId);

    //     if ($product) {
    //         $this->form->product_price = $product->price ?? 0;
    //     } else {
    //         $this->form->product_price = 0; // Reset if no product is found
    //     }
    // }

    protected $listeners = ['productChanged' => 'updateDefaultPrice'];

    public function mount()
    {
        $this->canCreate = $this->hasPermission(type: 'create');
        $this->redirectTo = request('redirect_to', '');
        $this->product = Product::where('company_id', Auth::user()->company_id)->get();
    }

    public function storeToSession()
    {
        $currentUrl = request()->header('referer', url()->previous());
        $this->storeFromSession();
        $this->redirectRoute('company.customer-management.customer-group.create', ['redirect_to' => $currentUrl]);
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $customer = $this->form->createCustomer();
        foreach ($this->selectedProducts as $product) {
            $product_company = Product::find($product['id']);
            CustomerProduct::create([

                'company_id' => $product_company->company_id,
                'productId' => $product['id'],
                'customer_id' => $customer->id,
                'product_price' => $product['product_price'],
                'product_price_new' => $product['product_price_new'],
                'customer_min_amount' => $product['customer_min_amount'],
            ]);
        }
        $this->purgeFromSession();
        flashAlert(__('app.panel.store', ['name' => __('company.customer-management.customer')]), 'success');
        if ($this->redirectTo) {

            redirect($this->redirectTo);
        } else {
            $this->redirectRoute('company.customer-management.customer.index');
        }

    }

    public function addProduct()
    {

        if ($this->form->productId === 'all') {
            $messagesall = [
                'form.product_price_new.*.required' => __('Special price is required'),
                'form.product_price_new.*.numeric' => __('Special price must be a number'),
                'form.product_price_new.*.gt' => __('Special price must be greater than 0'),

                'form.product_price.*.required' => __('Default price is required'),
                'form.product_price.*.numeric' => __('Default price must be a number'),
                'form.product_price.*.gt' => __('Default price must be greater than 0'),
                'form.customer_min_amount.*.numeric' => __('Customer minimum amount must be a number'),
                'form.customer_min_amount.*.gt' => __('Customer minimum amount must be greater than or equal to 0'),
            ];

            $this->validate([
                'form.product_price_new.*' => ['required', 'numeric', 'gt:0'],
                'form.product_price.*' => ['required', 'numeric', 'gt:0'],
                'form.customer_min_amount.*' => ['nullable', 'numeric', 'gt:-1'],
            ], $messagesall);
        } else {

            $messages = [
                'form.productId.required' => __('The product is required'),
                'form.product_price_new.required_if' => __('The price is required'),
                'form.product_price.required_if' => __('The default price is required'),
                'form.product_price_new.numeric' => __('The price must be a number'), // Custom numeric message

                'form.customer_min_amount.numeric' => __('Customer minimum amount must be a number'),
                'form.customer_min_amount.gt' => __('Customer minimum amount must be greater than or equal to 0'),
            ];

            // Validate fields with custom messages
            $this->validate([
                'form.productId' => 'required',
                'form.product_price_new' => 'required_if:form.productId,!=,null|numeric', // Ensure it's numeric
                'form.product_price' => 'required_if:form.productId,!=,null|numeric',
                'form.customer_min_amount' => 'nullable|numeric|gt:-1',
            ], $messages);

        }

        if (! $this->form->productId) {
            session()->flash('error', 'Please select a product.');

            return;
        }

        if ($this->form->productId === 'all') {

            $product_all = Product::where('company_id', Auth::user()->company_id)->get();

            $afterDeleteProductIds = collect($this->afterdeleteProducts)->pluck('id')->all();
            $alreadyAddedIds = collect($this->selectedProducts)->pluck('id')->all();

            foreach ($product_all as $single_product) {
                $id = $single_product->id;

                // Skip if in afterDelete or already added
                if (in_array($id, $afterDeleteProductIds) || in_array($id, $alreadyAddedIds)) {
                    continue;
                }
                $this->selectedProducts[] = [
                    'name' => $single_product->name,
                    'id' => $id,
                    'product_price_new' => data_get($this->form, "product_price_new.{$id}", 0),
                    'product_price' => data_get($this->form, "product_price.{$id}", 0),
                    'customer_min_amount' => data_get($this->form, "customer_min_amount.{$id}", null),
                    'product_min_amount' => data_get($this->form, "product_min_amount.{$id}", null),
                ];
            }

            if (empty($this->selectedProducts)) {
                session()->flash('error', 'All products are already added or deleted.');
            }
        } else {

            $product = Product::find($this->form->productId);
            $productExists = collect($this->selectedProducts)->contains('id', $product->id);

            if ($productExists) {
                session()->flash('error', 'This product is already added.');

                return;
            }

            $this->selectedProducts[] = [
                'name' => $product->name,
                'id' => $this->form->productId,
                'product_price_new' => $this->form->product_price_new,
                'product_price' => $this->form->product_price,
                'customer_min_amount' => $this->form->customer_min_amount,
                'product_min_amount' => $this->form->product_min_amount,
            ];

            $this->lastAddedProduct = [
                'productId' => $product->id,
                'product_price_new' => $this->form->product_price_new,
                'product_price' => $this->form->product_price,
                'customer_min_amount' => $this->form->customer_min_amount,
                'product_min_amount' => $this->form->product_min_amount,
            ];
            $this->form->productId = null;
            $this->form->product_price_new = null;
            $this->form->product_price = null;
            $this->form->product_min_amount = null;
            $this->form->customer_min_amount = null;
            $this->setLastAddedProduct();
        }
    }

    public function setLastAddedProduct()
    {
        if ($this->lastAddedProduct) {
            $this->form->productId = $this->lastAddedProduct['productId'];
            $this->form->product_price_new = $this->lastAddedProduct['product_price_new'];
            $this->form->product_price = $this->lastAddedProduct['product_price'];
            $this->form->product_min_amount = $this->lastAddedProduct['product_min_amount'];
            $this->form->customer_min_amount = $this->lastAddedProduct['customer_min_amount'];
        }
    }

    public function removeProduct($id)
    {

        $this->selectedProducts = array_values(
            array_filter($this->selectedProducts, fn ($product) => (string) $product['id'] !== (string) $id)
        );
    }

    public function updateDefaultPrice($productId)
    {
        logger()->info('Livewire received event productChanged with Product ID:', [$productId]); // Debugging log
        logger('Product ID:', [$productId]);
        // Another way to log
        $this->form->productId = $productId;

        if ($productId === 'all') {
            // Loop through all products and store their default prices
            $this->form->product_price = [];
            $this->form->product_price_new = [];
            $this->form->customer_min_amount = [];
            $this->form->product_min_amount = [];
            $product_all = Product::where('company_id', Auth::user()->company_id)->get();
            foreach ($product_all as $product) {
                $this->form->product_price[$product->id] = $product->price ?? 0;
                $this->form->product_price_new[$product->id] = $product->price ?? 0;
                $this->form->product_min_amount[$product->id] = $product->minimum_price ?? 0;
                $this->form->customer_min_amount[$product->id] = null;
            }
        } else {
            $product = Product::find($productId);

            if ($product) {
                $this->form->product_price = $product->price ?? 0;
                $this->form->product_price_new = $product->price ?? 0;
                $this->form->customer_min_amount = 0;
                $this->form->product_min_amount = $product->minimum_price ?? 0;
            } else {
                $this->form->product_price = 0; // Reset if no product is found
                $this->form->product_price_new = 0;
                $this->form->customer_min_amount = 0;
                $this->form->product_min_amount = 0;
            }
        }
        logger('Updated form.product_price:', [$this->form->product_price]);

    }

    public function deleteProductRow($id)
    {
        $this->product = $this->product->filter(function ($product) use ($id) {
            return $product->id !== $id;
        })->values(); // reset keys

        // Remove from selectedProducts array as well
        $this->selectedProducts = collect($this->selectedProducts)
            ->reject(function ($product) use ($id) {
                return $product['id'] === $id;
            })->values()->toArray();
        $this->afterdeleteProducts[] = ['id' => $id];
        // Remove associated prices from the form object
        if (isset($this->form->product_price[$id])) {
            unset($this->form->product_price[$id]);
        }

        if (isset($this->form->product_price_new[$id])) {
            unset($this->form->product_price_new[$id]);
        }
        if (isset($this->form->customer_min_amount[$id])) {
            unset($this->form->customer_min_amount[$id]);
        }
        
        if (isset($this->form->product_min_amount[$id])) {
            unset($this->form->product_min_amount[$id]);
        }
    }

    public function render(): View
    {

        $title = __('app.panel.create_name', ['name' => __('company.customer-management.customer')]);
        $customerGroups = CustomerGroup::where('company_id', Auth::user()->company_id)->get();
        $product = Product::where('company_id', Auth::user()->company_id)->get();

        $company = Company::where('id', Auth::user()->company_id)->first();

        if ($this->canCreate) {
            return view('company::CustomerManagement.Customer.views.form', [
                'title' => $title,
                'company_type' => $company->company_registor_type,
                'customerGroups' => $customerGroups,
                'product' => $product,
            ])->layout(
                'panel::layout.app',
                [
                    'breadcrumb' => [
                        [str()->plural(__('company.customer-management.customer')), route('company.customer-management.customer.index')],
                        [__('app.panel.create'), route('company.customer-management.customer.create')],
                    ],
                    'title' => $title,
                ]
            );
        } else {
            abort(403);
        }
    }
}
