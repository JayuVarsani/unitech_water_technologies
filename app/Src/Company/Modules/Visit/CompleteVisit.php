<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Visit;

use App\Models\Staff;
use App\Models\Visit;
use App\Src\Company\Modules\Visit\Form\VisitForm;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CompleteVisit extends Component
{
    #[Locked]
    public Visit $visit;

    public VisitForm $form;

    public $canComplete;

    protected ?string $moduleUniqueName = 'company.visit';

    public function mount(Visit $visit): void
    {
        abort_if($visit->company_id !== Auth::user()->company_id, 404);
        abort_unless($visit->isPending(), 404);

        $this->canComplete = $this->hasPermission(type: 'edit');
        $this->visit = $visit->load('amc');
        $this->form->setVisit($visit);

        if (empty($this->form->visitDate)) {
            $this->form->visitDate = now()->format('Y-m-d');
        }

        if ($this->visit->amc) {
            $amc = $this->visit->amc;
            $this->form->amcPeriod = $amc->from_date?->format('d-m-Y').' to '.$amc->to_date?->format('d-m-Y');
        }
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->completeVisit($this->visit);
        flashAlert('Visit completed successfully.', 'success');
        $this->redirectRoute('company.visit.index');
    }

    public function render(): View
    {
        $title = 'Complete Visit';
        $staffs = Staff::where('company_id', Auth::user()->company_id)->orderBy('name')->get(['id', 'name']);

        if ($this->canComplete) {
            return view('company::Visit.views.form', [
                'title' => $title,
                'completeMode' => true,
                'staffs' => $staffs,
            ])->layout('panel::layout.app', [
                'breadcrumb' => [
                    ['Visits', route('company.visit.index')],
                    ['Complete Visit', route('company.visit.complete', $this->visit->id)],
                ],
                'title' => $title,
            ]);
        }

        abort(403);
    }
}
