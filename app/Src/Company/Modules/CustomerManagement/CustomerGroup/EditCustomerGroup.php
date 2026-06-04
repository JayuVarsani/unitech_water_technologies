<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\CustomerManagement\CustomerGroup;

use App\Models\CustomerGroup;
use App\Src\Company\Modules\CustomerManagement\CustomerGroup\Form\CustomerGroupForm;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditCustomerGroup extends Component
{
    #[Locked]
    public CustomerGroup $customergroup;

    public CustomerGroupForm $form;

    public $canEdit;

    protected ?string $moduleUniqueName = 'company.customer-group';

    public function mount(CustomerGroup $customergroup): void
    {
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->customergroup = $customergroup;
        $this->form->setCustomerGroup($customergroup);
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->update($this->customergroup);
        flashAlert(__('app.panel.update', ['name' => __('company.customer-group')]), 'success');
        $this->redirectRoute('company.customer-management.customer-group.index');
    }

    public function render(): View
    {
        $title = __('app.panel.edit_name', ['name' => __('company.customer-group')]);

        if ($this->canEdit) {
            return view('company::CustomerManagement.CustomerGroup.views.form', [
                'title' => $title,

            ])
                ->layout('panel::layout.app', [
                    'breadcrumb' => [
                        [str()->plural(__('company.customer-group')), route('company.customer-management.customer-group.index')],
                        [__('app.panel.edit'), route('company.customer-management.customer-group.edit', $this->customergroup->id)],
                    ],
                    'title' => $title,
                ]);
        } else {
            abort(403);
        }
    }
}
