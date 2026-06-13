<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Visit;

use App\Models\Visit;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewVisit extends Component
{
    public Visit $visit;

    public $canView;

    public $canEdit;

    protected ?string $moduleUniqueName = 'company.visit';

    public function mount(Visit $visit): void
    {
        abort_if($visit->company_id !== Auth::user()->company_id, 404);

        $this->visit = $visit;
        $this->canView = $this->hasPermission(type: 'view');
        $this->canEdit = $this->hasPermission(type: 'edit');
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function render(): View
    {
        $title = __('app.panel.view_name', ['name' => 'Visit']);

        if ($this->canView) {
            return view('company::Visit.views.view', [
                'title' => $title,
            ])->layout('panel::layout.app', [
                'breadcrumb' => [
                    ['Visits', route('company.visit.index')],
                    [__('app.panel.view'), route('company.visit.view', $this->visit->id)],
                ],
                'title' => $title,
            ]);
        }

        abort(403);
    }
}
