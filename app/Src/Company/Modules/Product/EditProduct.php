<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Product;

use App\Models\Category;
use App\Models\Material;
use App\Models\Product;
use App\Models\Unit;
use App\Src\Company\Modules\Product\Form\ProductForm;
use App\Utility\Traits\FormSessionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditProduct extends Component
{
    use FormSessionTrait;

    public $formSessionName = 'productedit';

    #[Locked]
    public Product $product;

    public ProductForm $form;

    public $canEdit;

    public $canCreateCategory;

    public $selectedMaterials = [];

    protected ?string $moduleUniqueName = 'company.product';

    protected ?string $moduleUniqueNamecat = 'company.category';

    public function mount(Product $product): void
    {
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->canCreateCategory = $this->hasPermissioncategory(type: 'create');
        $this->product = $product;
        $this->form->setProduct($product);
        $this->selectedMaterials = $product->materials->map(fn ($material) => [
            'id' => $material->id,
            'name' => $material->material_name,
        ])->toArray();
    }

    public function hasPermissioncategory(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueNamecat ? auth()->user()->hasPermission($this->moduleUniqueNamecat, $type, $abort) : true;
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function storeToSession()
    {
        $currentUrl = request()->header('referer', url()->previous());
        $this->storeFromSession();
        $this->redirectRoute('company.category.create', ['redirect_to' => $currentUrl]);
    }

    public function save(): void
    {
        if (empty($this->selectedMaterials)) {

            $this->addError('form.materialId', 'Please select at least one material.');

            return;
        }
        $this->product->materials()->sync(array_column($this->selectedMaterials, 'id'));
        $this->form->update($this->product);
        flashAlert(__('app.panel.update', ['name' => __('company.product')]), 'success');
        $this->redirectRoute('company.product.index');
    }

    public function addMaterial(): void
    {
        $material = Material::find($this->form->materialId);
        if ($material && ! in_array($material->id, array_column($this->selectedMaterials, 'id'))) {
            $this->selectedMaterials[] = [
                'id' => $material->id,
                'name' => $material->material_name,
            ];
            $this->updateProductName();
            // $this->form->materialId = null; // Reset the selected material after adding
        }
    }

    public function removeMaterial($materialId): void
    {
        $this->selectedMaterials = array_filter($this->selectedMaterials, function ($material) use ($materialId) {
            return $material['id'] !== $materialId;
        });
        $this->updateProductName();
    }

    public function render(): View
    {
        $title = __('app.panel.edit_name', ['name' => __('company.product')]);
        $productUnits = Unit::all();
        $categories = Category::where('company_id', Auth::user()->company_id)->get();
        $material = Material::where('company_id', Auth::user()->company_id)->get();

        if ($this->canEdit) {
            return view('company::Product.views.form', [
                'title' => $title,
                'productUnits' => $productUnits,
                'categories' => $categories,
                'material' => $material,
            ])
                ->layout('panel::layout.app', [
                    'breadcrumb' => [
                        [str()->plural(__('company.product')), route('company.product.index')],
                        [__('app.panel.edit'), route('company.product.edit', $this->product->id)],
                    ],
                    'title' => $title,
                ]);
        } else {
            abort(403);
        }
    }

    private function updateProductName()
    {
        $materialNames = array_column($this->selectedMaterials, 'name');
        $this->form->name = implode(' + ', $materialNames); // Concatenate material names
    }
}
