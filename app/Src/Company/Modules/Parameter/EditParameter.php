<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Parameter;

use App\Models\Parameter;
use App\Src\Company\Modules\Parameter\Form\ParameterForm;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditParameter extends Component
{
    #[Locked]
    public Parameter $parameter;

    public ParameterForm $form;

    public $canEdit;

    protected ?string $moduleUniqueName = 'company.parameter';

    public function mount(Parameter $parameter): void
    {
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->parameter = $parameter;
        $this->form->setParameter($parameter);
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->update($this->parameter);
        flashAlert(__('app.panel.update', ['name' => 'Parameter']), 'success');
        $this->redirectRoute('company.parameter.index');
    }

    public function render(): View
    {
        $title = __('app.panel.edit_name', ['name' => 'Parameter']);

        if ($this->canEdit) {
            return view('company::Parameter.views.form', ['title' => $title])
                ->layout('panel::layout.app', [
                    'breadcrumb' => [
                        ['Parameters', route('company.parameter.index')],
                        [__('app.panel.edit'), route('company.parameter.edit', $this->parameter->id)],
                    ],
                    'title' => $title,
                ]);
        }

        abort(403);
    }
}
