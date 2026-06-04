<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\CustomerManagement\Customer;

use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\Product;
use App\Src\Company\Modules\CustomerManagement\Customer\Form\CustomerForm;
use App\Utility\Traits\FormSessionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditCustomer extends Component
{
    use FormSessionTrait;

    #[Locked]
    public Customer $customer;

    public CustomerForm $form;

    public $canEdit;

    public $selectedProducts = [];

    public $lastAddedProduct = null;

    public $formSessionName = 'customeredit';

    public $product = [];

    public $afterdeleteProducts = [];

    protected ?string $moduleUniqueName = 'company.customer';

    protected $listeners = ['productChanged' => 'updateDefaultPrice'];

    public function mount(Customer $customer): void
    {
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->customer = $customer;
        $this->form->setCustomer($customer);

        $this->selectedProducts = $customer->products->map(fn ($product) => [
            'id' => $product->id,
            'name' => $product->name,
            'product_price_new' => $product->pivot->product_price_new,
            'product_price' => $product->pivot->product_price,
            'customer_min_amount' => $product->pivot->customer_min_amount,
            'product_min_amount' => $product->minimum_price,
        ])->toArray();

        if (! empty($this->selectedProducts)) {
            $lastProduct = end($this->selectedProducts);
            $this->form->product_price = $lastProduct['product_price'];
            $this->form->product_price_new = $lastProduct['product_price_new'];
            $this->form->customer_min_amount = $lastProduct['customer_min_amount'];
            $this->form->product_min_amount = $lastProduct['product_min_amount'];
            $this->form->productId = $lastProduct['id'];
        }
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
        $this->validateProducts();
        $customerId = $this->customer->id;
        $companyId = $this->customer->company_id;

        $pivotData = [];
        foreach ($this->selectedProducts as $product) {
            $pivotData[$product['id']] = [
                'company_id' => $companyId,
                'product_price' => $product['product_price'],
                'product_price_new' => $product['product_price_new'],
                'customer_min_amount' => (float) $product['customer_min_amount'],
            ];
        }

        $this->customer->products()->sync($pivotData);

        $this->form->update($this->customer);
        flashAlert(__('app.panel.update', ['name' => __('company.customer-management.customer')]), 'success');
        $this->redirectRoute('company.customer-management.customer.index');
    }

    public function validateProducts()
    {
        $messagesall = [
            'selectedProducts.*.id.required' => __('Product is required'),
            'selectedProducts.*.id.exists' => __('Product not found'),
            'selectedProducts.*.product_price_new.required' => __('Special price is required'),
            'selectedProducts.*.product_price_new.numeric' => __('Special price must be a number'),
            'selectedProducts.*.product_price_new.gt' => __('Special price must be greater than 0'),
            'selectedProducts.*.product_price.required' => __('Default price is required'),
            'selectedProducts.*.product_price.numeric' => __('Default price must be a number'),
            'selectedProducts.*.product_price.gt' => __('Default price must be greater than 0'),
            'selectedProducts.*.customer_min_amount.numeric' => __('Customer minimum amount must be a number'),
            'selectedProducts.*.customer_min_amount.gt' => __('Customer minimum amount must be greater than or equal to 0'),
            'selectedProducts.*.product_min_amount.numeric' => __('Product minimum amount must be a number'),
            'selectedProducts.*.product_min_amount.gt' => __('Product minimum amount must be greater than or equal to 0'),
        ];
        $this->validate([
            'selectedProducts.*.id' => 'required|exists:products,id',
            'selectedProducts.*.product_price_new' => 'required|numeric|gt:0',
            'selectedProducts.*.product_price' => 'required|numeric|gt:0',
            'selectedProducts.*.customer_min_amount' => 'nullable|numeric|gt:-1',
            'selectedProducts.*.product_min_amount' => 'nullable|numeric|gt:-1',
        ], $messagesall);
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

            // If "All" is selected, iterate through all products
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
            // $this->form->productId = null;
            // $this->form->product_price_new = null;
            // $this->form->product_price = null;
            $this->setLastAddedProduct();
        }
        // dd($this->selectedProducts);

    }

    public function setLastAddedProduct()
    {
        if ($this->lastAddedProduct) {
            $this->form->productId = $this->lastAddedProduct['productId'];
            $this->form->product_price_new = $this->lastAddedProduct['product_price_new'];
            $this->form->product_price = $this->lastAddedProduct['product_price'];
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
        $this->form->productId = $productId;

        if ($productId === 'all') {
            // Loop through all products and store their default prices
            $this->form->product_price = []; // Initialize as an array
            $this->form->product_price_new = [];
            $this->form->customer_min_amount = [];
            $this->form->product_min_amount = [];
            $product_all = Product::where('company_id', Auth::user()->company_id)->get();

            foreach ($product_all as $product) {
                $this->form->product_price[$product->id] = $product->price ?? 0;
                // $this->form->product_price_new[$product->id] = 0;
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
    }

    public function deleteProductRow($id)
    {
        // Ensure productnew is a collection
        $this->product = collect($this->product)->filter(function ($product) use ($id) {
            return $product->id !== $id;
        })->values();

        // Remove from selectedProducts
        $this->selectedProducts = collect($this->selectedProducts)
            ->reject(fn ($product) => $product['id'] === $id)
            ->values()
            ->toArray();

        // Track deleted
        $this->afterdeleteProducts[] = ['id' => $id];

        // Remove from form
        unset($this->form->product_price[$id]);
        unset($this->form->product_price_new[$id]);
        unset($this->form->customer_min_amount[$id]);
        unset($this->form->product_min_amount[$id]);
    }

    public function render(): View
    {
        $title = __('app.panel.edit_name', ['name' => __('company.customer-management.customer')]);
        $customerGroups = CustomerGroup::where('company_id', Auth::user()->company_id)->get();
        $product = Product::where('company_id', Auth::user()->company_id)->get();
        $company = Company::where('id', Auth::user()->company_id)->first();

        if ($this->canEdit) {
            return view(
                'company::CustomerManagement.Customer.views.form',
                ['title' => $title, 'customerGroups' => $customerGroups, 'product' => $product, 'company_type' => $company->company_registor_type])
                ->layout('panel::layout.app', [
                    'breadcrumb' => [
                        [str()->plural(__('company.customer-management.customer')), route('company.customer-management.customer.index')],
                        [__('app.panel.edit'), route('company.customer-management.customer.edit', $this->customer->id)],
                    ],
                    'title' => $title,
                ]);
        } else {
            abort(403);
        }
    }
}
