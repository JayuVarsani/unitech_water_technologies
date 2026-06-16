<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Parameter;

use App\Src\Company\Modules\Parameter\Form\ParameterForm;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreateParameter extends Component
{
    public ParameterForm $form;

    public $canCreate;

    protected ?string $moduleUniqueName = 'company.parameter';

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
        $this->form->createParameter();
        flashAlert(__('app.panel.store', ['name' => 'Parameter']), 'success');
        $this->redirectRoute('company.parameter.index');
    }

    public function render(): View
    {
        $title = __('app.panel.create_name', ['name' => 'Parameter']);

        if ($this->canCreate) {
            return view('company::Parameter.views.form', [
                'title' => $title,
            ])->layout(
                'panel::layout.app',
                [
                    'breadcrumb' => [
                        ['Parameters', route('company.parameter.index')],
                        [__('app.panel.create'), route('company.parameter.create')],
                    ],
                    'title' => $title,
                ]
            );
        }

        abort(403);
    }
}
