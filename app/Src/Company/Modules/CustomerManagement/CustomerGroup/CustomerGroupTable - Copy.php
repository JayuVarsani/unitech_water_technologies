<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\CustomerManagement\CustomerGroup;

use App\Models\CustomerGroup;
use App\Src\Company\Modules\CustomerManagement\CustomerGroup\Form\CustomerGroupForm;
use App\Utility\livewire\BaseTable;
// use App\Utility\livewire\ExceptionTrait;
use App\Utility\livewire\TableForm;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Rule;

class CustomerGroupTable extends BaseTable
{
    // use ExceptionTrait;

    public TableForm $query;

    public CustomerGroupForm $form;

    #[Locked]
    public CustomerGroup $customerGroup;

    #[Rule('required')]
    public $name;

    public $canView;

    public $canCreate;

    public $canEdit;

    public $canDelete;

    protected ?string $moduleUniqueName = 'company.customer-group';

    public function mount(CustomerGroup $customerGroup)
    {
        $this->canView = $this->hasPermission(type: 'view');
        $this->canCreate = $this->hasPermission(type: 'create');
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->canDelete = $this->hasPermission(type: 'delete');

        $this->customerGroup = $customerGroup;
        $this->form->setCustomerGroup($customerGroup);
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        if ($this->customerGroup->exists) {
            $this->form->update($this->customerGroup);
            flashAlert(__('app.panel.update', ['name' => __('company.customer-group')]), 'success');
        } else {
            $this->form->createCustomerGroup();
            flashAlert(__('app.panel.store', ['name' => __('company.customer-group')]), 'success');
        }
        $this->resetInput();
        $this->customerGroup = new CustomerGroup;
        $this->form->reset();
        $this->dispatch('close-modal');
        $this->redirectRoute('company.customer-management.customer-group.index');
    }

    public function edit(CustomerGroup $customerGroup): void
    {

        // $this->customerGroup = $customerGroup;
        $this->customerGroup = $customerGroup->fresh();
        $this->form->setCustomerGroup($customerGroup);
        $this->dispatch('openModal');
    }

    public function resetInput()
    {
        $this->name = '';
        $this->customerGroup = new CustomerGroup; // Reset the model instance
        $this->form->reset();
    }

    public function render(): View
    {
        $editTitle = __('app.panel.edit_name', ['name' => __('company.customer-group')]);
        if ($this->customerGroup->exists) {
            $modalTitle = $editTitle;
        } else {
            $modalTitle = __('app.panel.create_name', ['name' => __('company.customer-group')]);
        }

        return view('company::CustomerManagement.CustomerGroup.views.table', [
            'items' => $this->dataSource(),
            'modalTitle' => $modalTitle,
        ])->layout('panel::layout.app', ['title' => str()->plural(__('company.customer-group'))]);
    }

    public function delete(CustomerGroup $customerGroup): void
    {
        $customerGroup->delete();
        flashAlert(__('app.panel.delete', ['name' => __('company.customer-group')]), 'success');
    }

    protected function dataSource(): LengthAwarePaginator
    {
        return CustomerGroup::select(['name', 'id', 'company_id'])->where('company_id', Auth::user()->company_id)->when($this->query->search, function (Builder $query) {
            return $query->where(function (Builder $query) {
                return $query->whereAny(['name', 'id'], 'like', "%{$this->query->search}%");
            });
        })->latest('id')->paginate($this->query->perPage);
    }
}
