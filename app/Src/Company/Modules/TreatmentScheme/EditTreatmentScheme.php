<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\TreatmentScheme;

use App\Models\TreatmentScheme;
use App\Src\Company\Modules\TreatmentScheme\Form\TreatmentSchemeForm;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditTreatmentScheme extends Component
{
    #[Locked]
    public TreatmentScheme $treatmentScheme;

    public TreatmentSchemeForm $form;

    public $canEdit;

    protected ?string $moduleUniqueName = 'company.treatment-scheme';

    public function mount(TreatmentScheme $treatmentScheme): void
    {
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->treatmentScheme = $treatmentScheme;
        $this->form->setTreatmentScheme($treatmentScheme);
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->update($this->treatmentScheme);
        flashAlert(__('app.panel.update', ['name' => 'Treatment Scheme']), 'success');
        $this->redirectRoute('company.treatment-scheme.index');
    }

    public function render(): View
    {
        $title = __('app.panel.edit_name', ['name' => 'Treatment Scheme']);

        if ($this->canEdit) {
            return view('company::TreatmentScheme.views.form', ['title' => $title])
                ->layout('panel::layout.app', [
                    'breadcrumb' => [
                        ['Treatment Schemes', route('company.treatment-scheme.index')],
                        [__('app.panel.edit'), route('company.treatment-scheme.edit', $this->treatmentScheme->id)],
                    ],
                    'title' => $title,
                ]);
        }

        abort(403);
    }
}
