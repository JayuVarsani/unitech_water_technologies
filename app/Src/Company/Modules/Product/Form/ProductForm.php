<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Product\Form;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProductForm extends Form
{
    #[Validate]
    public $name;

    #[Validate]
    public $unitId;

    // #[Validate]
    public $stock;

    #[Validate]
    public $price;

    public $minimum_price;

    #[Validate]
    public $productId;

    #[Validate]
    public $categoryId;

    // #[Validate]
    public $sku;

    #[Validate]
    public $id = 0;

    #[Validate]
    public $materialId;

    // #[Validate]
    public $selectedMaterials = [];

    public function createProduct(): Product
    {

        $this->validate();
        $product = Product::create($this->getValues());

        return $product;

    }

    public function rules(): array
    {
        return [
            'name' => ['required', Rule::unique(Product::class, 'name')->where(fn ($query) => $query->where('company_id', Auth::user()->company_id))->ignore($this->id, 'id')],
            'unitId' => ['required'],
            // 'stock' => ['required'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'productId' => ['required', Rule::unique(Product::class, 'product_id')->where(fn ($query) => $query->where('company_id', Auth::user()->company_id))->ignore($this->id)],
            'categoryId' => ['required'],
            'materialId' => [Rule::requiredIf(empty($this->selectedMaterials))],
            'minimum_price' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            // 'selectedMaterials' => ['required', 'array', 'min:1'],
            // 'sku' => ['required', Rule::unique(Product::class, 'sku')->ignore($this->id)],
        ];
    }

    public function setProduct(Product $product): void
    {
        $this->id = $product->id;

        $this->fill([
            'name' => $product->name,
            'unitId' => $product->unit_id,
            // 'stock' => $product->stock,
            'price' => $product->price,
            'minimum_price' => $product->minimum_price,
            'productId' => $product->product_id,
            'categoryId' => $product->category_id,
            // 'sku' => $product->sku,
            'selectedMaterials' => $product->materials->pluck('id')->toArray(),
        ]);
    }

    public function update(Product $product): void
    {
        $this->validate();
        $product->update($this->getValues());
    }

    protected function getValues(): array
    {
        $unit = Unit::where('id', $this->unitId)->first();
        $catetory = Category::where('id', $this->categoryId)->where('company_id', Auth::user()->company_id)->first();

        return [
            'name' => ucfirst($this->name ?? ''),
            'unit_id' => $this->unitId,
            'unit_name' => $unit->name,
            // 'stock' => $this->stock,
            'price' => $this->price,
            'minimum_price' => floatval($this->minimum_price ?? '0'),
            'product_id' => $this->productId,
            'category_id' => $this->categoryId,
            'category_name' => $catetory->name,
            // 'sku' => $this->sku,
            'company_id' => Auth::user()->company_id,
        ];
    }
}
