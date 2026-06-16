<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\TreatmentScheme;

use App\Src\Company\Modules\TreatmentScheme\Form\TreatmentSchemeForm;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreateTreatmentScheme extends Component
{
    public TreatmentSchemeForm $form;

    public $canCreate;

    protected ?string $moduleUniqueName = 'company.treatment-scheme';

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
        $this->form->createTreatmentScheme();
        flashAlert(__('app.panel.store', ['name' => 'Treatment Scheme']), 'success');
        $this->redirectRoute('company.treatment-scheme.index');
    }

    public function render(): View
    {
        $title = __('app.panel.create_name', ['name' => 'Treatment Scheme']);

        if ($this->canCreate) {
            return view('company::TreatmentScheme.views.form', [
                'title' => $title,
            ])->layout(
                'panel::layout.app',
                [
                    'breadcrumb' => [
                        ['Treatment Schemes', route('company.treatment-scheme.index')],
                        [__('app.panel.create'), route('company.treatment-scheme.create')],
                    ],
                    'title' => $title,
                ]
            );
        }

        abort(403);
    }
}
