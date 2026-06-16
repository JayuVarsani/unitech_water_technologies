<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Installation;

use App\Models\Installation;
use App\Models\Parameter;
use App\Models\TreatmentScheme;
use App\Src\Company\Modules\Installation\Form\InstallationForm;
use App\Utility\Traits\FormSessionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditInstallation extends Component
{
    use FormSessionTrait;

    public $formSessionName = 'installationedit';

    #[Locked]
    public Installation $installation;

    public InstallationForm $form;

    public $canEdit;

    protected ?string $moduleUniqueName = 'company.installation';

    public function mount(Installation $installation): void
    {
        abort_if($installation->company_id !== Auth::user()->company_id, 404);

        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->installation = $installation->load(['installationParameters', 'installationTreatmentSchemes']);
        $this->form->setInstallation($this->installation);
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->update($this->installation);
        flashAlert(__('app.panel.update', ['name' => 'Installation']), 'success');
        $this->redirectRoute('company.installation.index');
    }

    public function render(): View
    {
        $title = __('app.panel.edit_name', ['name' => 'Installation']);
        $companyId = Auth::user()->company_id;
        $parameters = Parameter::where('company_id', $companyId)->orderBy('name')->get(['id', 'name', 'unit']);
        $treatmentSchemes = TreatmentScheme::where('company_id', $companyId)->orderBy('name')->get(['id', 'name']);

        if ($this->canEdit) {
            return view('company::Installation.views.form', [
                'title' => $title,
                'parameters' => $parameters,
                'treatmentSchemes' => $treatmentSchemes,
            ])->layout('panel::layout.app', [
                'breadcrumb' => [
                    ['Installations', route('company.installation.index')],
                    [__('app.panel.edit'), route('company.installation.edit', $this->installation->id)],
                ],
                'title' => $title,
            ]);
        }

        abort(403);
    }
}
