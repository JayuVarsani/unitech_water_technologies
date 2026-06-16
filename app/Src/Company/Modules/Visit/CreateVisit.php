<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Visit;

use App\Src\Company\Modules\Visit\Form\VisitForm;
use App\Utility\Traits\FormSessionTrait;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreateVisit extends Component
{
    use FormSessionTrait;

    public $formSessionName = 'visit';

    public VisitForm $form;

    public $canCreate;

    protected ?string $moduleUniqueName = 'company.visit';

    public function mount(): void
    {
        abort(404);
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->createVisit();
        $this->purgeFromSession();
        flashAlert(__('app.panel.store', ['name' => 'Visit']), 'success');
        $this->redirectRoute('company.visit.index');
    }

    public function render(): View
    {
        $title = __('app.panel.create_name', ['name' => 'Visit']);

        if ($this->canCreate) {
            return view('company::Visit.views.form', [
                'title' => $title,
            ])->layout(
                'panel::layout.app',
                [
                    'breadcrumb' => [
                        ['Visits', route('company.visit.index')],
                        [__('app.panel.create'), route('company.visit.create')],
                    ],
                    'title' => $title,
                ]
            );
        }

        abort(403);
    }
}
