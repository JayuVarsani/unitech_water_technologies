<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Installation;

use App\Models\Parameter;
use App\Models\TreatmentScheme;
use App\Src\Company\Modules\Installation\Form\InstallationForm;
use App\Utility\Traits\FormSessionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateInstallation extends Component
{
    use FormSessionTrait;

    public $formSessionName = 'installation';

    public InstallationForm $form;

    public $canCreate;

    protected ?string $moduleUniqueName = 'company.installation';

    public function mount(): void
    {
        $this->canCreate = $this->hasPermission(type: 'create');

        if (empty($this->form->installationDate)) {
            $this->form->installationDate = now()->format('Y-m-d');
        }
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->createInstallation();
        $this->purgeFromSession();
        flashAlert(__('app.panel.store', ['name' => 'Installation']), 'success');
        $this->redirectRoute('company.installation.index');
    }

    public function render(): View
    {
        $title = __('app.panel.create_name', ['name' => 'Installation']);
        $companyId = Auth::user()->company_id;
        $parameters = Parameter::where('company_id', $companyId)->orderBy('name')->get(['id', 'name', 'unit']);
        $treatmentSchemes = TreatmentScheme::where('company_id', $companyId)->orderBy('name')->get(['id', 'name']);

        if ($this->canCreate) {
            return view('company::Installation.views.form', [
                'title' => $title,
                'parameters' => $parameters,
                'treatmentSchemes' => $treatmentSchemes,
            ])->layout('panel::layout.app', [
                'breadcrumb' => [
                    ['Installations', route('company.installation.index')],
                    [__('app.panel.create'), route('company.installation.create')],
                ],
                'title' => $title,
            ]);
        }

        abort(403);
    }
}
