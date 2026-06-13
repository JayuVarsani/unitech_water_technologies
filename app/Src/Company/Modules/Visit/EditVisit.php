<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Visit;

use App\Models\Visit;
use App\Src\Company\Modules\Visit\Form\VisitForm;
use App\Utility\Traits\FormSessionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditVisit extends Component
{
    use FormSessionTrait;

    public $formSessionName = 'visitedit';

    #[Locked]
    public Visit $visit;

    public VisitForm $form;

    public $canEdit;

    protected ?string $moduleUniqueName = 'company.visit';

    public function mount(Visit $visit): void
    {
        abort_if($visit->company_id !== Auth::user()->company_id, 404);

        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->visit = $visit;
        $this->form->setVisit($visit);
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->update($this->visit);
        flashAlert(__('app.panel.update', ['name' => 'Visit']), 'success');
        $this->redirectRoute('company.visit.index');
    }

    public function render(): View
    {
        $title = __('app.panel.edit_name', ['name' => 'Visit']);

        if ($this->canEdit) {
            return view('company::Visit.views.form', [
                'title' => $title,
            ])->layout('panel::layout.app', [
                'breadcrumb' => [
                    ['Visits', route('company.visit.index')],
                    [__('app.panel.edit'), route('company.visit.edit', $this->visit->id)],
                ],
                'title' => $title,
            ]);
        }

        abort(403);
    }
}
