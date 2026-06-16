<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Installation;

use App\Models\Installation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewInstallation extends Component
{
    public Installation $installation;

    public $canView;

    public $canEdit;

    protected ?string $moduleUniqueName = 'company.installation';

    public function mount(Installation $installation): void
    {
        abort_if($installation->company_id !== Auth::user()->company_id, 404);

        $this->installation = $installation->load([
            'installationParameters.parameter',
            'installationTreatmentSchemes.treatmentScheme',
        ]);
        $this->canView = $this->hasPermission(type: 'view');
        $this->canEdit = $this->hasPermission(type: 'edit');
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function render(): View
    {
        $title = __('app.panel.view_name', ['name' => 'Installation']);

        if ($this->canView) {
            return view('company::Installation.views.view', [
                'title' => $title,
            ])->layout('panel::layout.app', [
                'breadcrumb' => [
                    ['Installations', route('company.installation.index')],
                    [__('app.panel.view'), route('company.installation.view', $this->installation->id)],
                ],
                'title' => $title,
            ]);
        }

        abort(403);
    }
}
