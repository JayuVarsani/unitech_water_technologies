<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Material;

use App\Models\Unit;
use App\Src\Company\Modules\Material\Form\MaterialForm;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreateMaterial extends Component
{
    public MaterialForm $form;

    public $canCreate;

    protected ?string $moduleUniqueName = 'company.material';

    public function mount()
    {
        $this->canCreate = $this->hasPermission(type: 'create');
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->createMaterial();

        flashAlert(__('app.panel.store', ['name' => __('company.material')]), 'success');
        $this->redirectRoute('company.material.index');
    }

    public function render(): View
    {
        $title = __('app.panel.create_name', ['name' => __('company.material')]);

        $productUnits = Unit::all();

        if ($this->canCreate) {
            return view('company::Material.views.form', [
                'title' => $title,
                'productUnits' => $productUnits,

            ])->layout(
                'panel::layout.app',
                [
                    'breadcrumb' => [
                        [str()->plural(__('company.material')), route('company.material.index')],
                        [__('app.panel.create'), route('company.material.create')],
                    ],
                    'title' => $title,
                ]
            );
        } else {
            abort(403);
        }
    }
}
