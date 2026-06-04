<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Product;

use App\Models\Category;
use App\Models\Material;
use App\Models\ProductMaterial;
use App\Models\Unit;
use App\Src\Company\Modules\Product\Form\ProductForm;
use App\Utility\Traits\FormSessionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateProduct extends Component
{
    use FormSessionTrait;

    public $formSessionName = 'product';

    public ProductForm $form;

    public $canCreate;

    public $canCreateCategory;

    public $selectedMaterials = [];

    protected ?string $moduleUniqueName = 'company.product';

    protected ?string $moduleUniqueNamecat = 'company.category';

    public function mount()
    {
        $this->canCreate = $this->hasPermission(type: 'create');
        $this->canCreateCategory = $this->hasPermissioncategory(type: 'create');
        $this->form->materialId = null;
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function hasPermissioncategory(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueNamecat ? auth()->user()->hasPermission($this->moduleUniqueNamecat, $type, $abort) : true;
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
        $product = $this->form->createProduct();

        foreach ($this->selectedMaterials as $material) {
            ProductMaterial::create([
                'product_id' => $product->id,
                'material_id' => $material['id'],
            ]);
        }
        $this->purgeFromSession();
        flashAlert(__('app.panel.store', ['name' => __('company.product')]), 'success');
        $this->redirectRoute('company.product.index');
    }

    public function addMaterial()
    {
        // Check if a material is selected and not already in the list
        if ($this->form->materialId && ! in_array($this->form->materialId, array_column($this->selectedMaterials, 'id'))) {
            $material = Material::find($this->form->materialId);
            if ($material) {
                $this->selectedMaterials[] = [
                    'id' => $material->id,
                    'name' => $material->material_name,
                ];
                $this->updateProductName();
                // $this->form->materialId = null; // Reset the dropdown

            }
        }
    }

    public function updatedFormMaterialId($value)
    {
        $this->resetValidation('form.categoryId');

    }

    public function removeMaterial($id)
    {
        $this->selectedMaterials = array_filter($this->selectedMaterials, fn ($material) => $material['id'] !== $id);
        $this->updateProductName();
    }

    public function render(): View
    {
        $title = __('app.panel.create_name', ['name' => __('company.product')]);

        $productUnits = Unit::all();
        $categories = Category::where('company_id', Auth::user()->company_id)->get();
        $material = Material::where('company_id', Auth::user()->company_id)->get();

        if ($this->canCreate) {
            return view('company::Product.views.form', [
                'title' => $title,
                'productUnits' => $productUnits,
                'categories' => $categories,
                'material' => $material,
            ])->layout(
                'panel::layout.app',
                [
                    'breadcrumb' => [
                        [str()->plural(__('company.product')), route('company.product.index')],
                        [__('app.panel.create'), route('company.product.create')],
                    ],
                    'title' => $title,
                ]
            );
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
