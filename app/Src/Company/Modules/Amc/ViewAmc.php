<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Amc;

use App\Models\Amc;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewAmc extends Component
{
    public Amc $amc;

    public $canView;

    public $canEdit;

    protected ?string $moduleUniqueName = 'company.amc';

    public function mount(Amc $amc): void
    {
        abort_if($amc->company_id !== Auth::user()->company_id, 404);

        $this->amc = $amc->load('visitMonths');
        $this->canView = $this->hasPermission(type: 'view');
        $this->canEdit = $this->hasPermission(type: 'edit');
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function render(): View
    {
        $title = __('app.panel.view_name', ['name' => 'AMC']);

        if ($this->canView) {
            return view('company::Amc.views.view', [
                'title' => $title,
            ])->layout('panel::layout.app', [
                'breadcrumb' => [
                    ['AMC', route('company.amc.index')],
                    [__('app.panel.view'), route('company.amc.view', $this->amc->id)],
                ],
                'title' => $title,
            ]);
        }

        abort(403);
    }
}
