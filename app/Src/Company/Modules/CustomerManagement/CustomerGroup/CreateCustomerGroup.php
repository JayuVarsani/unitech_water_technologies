<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\CustomerManagement\CustomerGroup;

use App\Src\Company\Modules\CustomerManagement\CustomerGroup\Form\CustomerGroupForm;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreateCustomerGroup extends Component
{
    public CustomerGroupForm $form;

    public $canCreate;

    public string $redirectTo = '';

    protected ?string $moduleUniqueName = 'company.customer-group';

    public function mount()
    {
        $this->canCreate = $this->hasPermission(type: 'create');
        $this->redirectTo = request('redirect_to', '');

    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->createCustomerGroup();

        flashAlert(__('app.panel.store', ['name' => __('company.customer-group')]), 'success');

        if ($this->redirectTo) {

            redirect($this->redirectTo);
        } else {
            $this->redirectRoute('company.customer-management.customer-group.index');
        }

    }

    public function render(): View
    {
        $title = __('app.panel.create_name', ['name' => __('company.customer-group')]);

        if ($this->canCreate) {
            return view('company::CustomerManagement.CustomerGroup.views.form', [
                'title' => $title,

            ])->layout(
                'panel::layout.app',
                [
                    'breadcrumb' => [
                        [str()->plural(__('company.customer-group')), route('company.customer-management.customer-group.index')],
                        [__('app.panel.create'), route('company.customer-management.customer-group.create')],
                    ],
                    'title' => $title,
                ]
            );
        } else {
            abort(403);
        }
    }
}
