<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Material;

use App\Models\Category;
use App\Models\Material;
use App\Models\Unit;
use App\Src\Company\Modules\Material\Form\MaterialForm;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditMaterial extends Component
{
    #[Locked]
    public Material $material;

    public MaterialForm $form;

    public $canEdit;

    protected ?string $moduleUniqueName = 'company.material';

    public function mount(Material $material): void
    {
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->material = $material;
        $this->form->setMaterial($material);
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->update($this->material);
        flashAlert(__('app.panel.update', ['name' => __('company.material')]), 'success');
        $this->redirectRoute('company.material.index');
    }

    public function render(): View
    {
        $title = __('app.panel.edit_name', ['name' => __('company.material')]);
        $productUnits = Unit::all();
        // $categories = Category::where('company_id', Auth::user()->company_id)->get();

        if ($this->canEdit) {
            return view('company::Material.views.form', [
                'title' => $title,
                'productUnits' => $productUnits,
                // 'categories' => $categories,

            ])
                ->layout('panel::layout.app', [
                    'breadcrumb' => [
                        [str()->plural(__('company.material')), route('company.material.index')],
                        [__('app.panel.edit'), route('company.material.edit', $this->material->id)],
                    ],
                    'title' => $title,
                ]);
        } else {
            abort(403);
        }
    }
}
