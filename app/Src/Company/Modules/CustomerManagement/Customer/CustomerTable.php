<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\CustomerManagement\Customer;

use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Utility\livewire\BaseTable;
use App\Utility\livewire\ExceptionTrait;
use App\Src\Company\Modules\CustomerManagement\Customer\Form\CustomerGroupTableQueryForm;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class CustomerTable extends BaseTable
{
    use ExceptionTrait;

    public CustomerGroupTableQueryForm $query;

    public $canView;

    public $canCreate;

    public $canEdit;

    public $canDelete;

    public $canViewOrder;

    protected ?string $moduleUniqueName = 'company.customer';

    public function mount()
    {
        $this->canView = $this->hasPermission(type: 'view');
        $this->canCreate = $this->hasPermission(type: 'create');
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->canDelete = $this->hasPermission(type: 'delete');
        $this->canViewOrder = $this->hasPermission(type: 'view', moduleUniqueName: 'company.order');
    }

    public function hasPermission(string $type = 'view', bool $abort = true, $moduleUniqueName = null): bool
    {
        $moduleUniqueName = $moduleUniqueName ?? $this->moduleUniqueName;
        return $moduleUniqueName ? auth()->user()->hasPermission($moduleUniqueName, $type, $abort) : true;
    }

    public function render(): View
    {
        $companyId = Auth::user()->company_id;

        return view('company::CustomerManagement.Customer.views.table', [
            'items' => $this->dataSource(),
            'customerGroups' => CustomerGroup::query()
                ->where('company_id', $companyId)
                ->orderBy('name')
                ->get(['id', 'name']),
        ])->layout('panel::layout.app', ['title' => str()->plural(__('company.customer-management.customers'))]);
    }

    public function delete(Customer $customer): void
    {
        $customer->delete();
        flashAlert(__('app.panel.delete', ['name' => __('company.customer-management.customer')]), 'success');
    }

    protected function dataSource(): LengthAwarePaginator
    {
        return Customer::select([
            'name',
            'id',
            'contact_number',
            'customer_group_name',
        ])
            ->where('company_id', Auth::user()->company_id)
            ->when($this->query->customerGroupId, function (Builder $query) {
                return $query->where('customer_group_id', (int) $this->query->customerGroupId);
            })
            ->when($this->query->search, function (Builder $query) {
                return $query->where(function (Builder $query) {
                    return $query->whereAny(['name', 'id', 'contact_number', 'customer_group_name'], 'like', "%{$this->query->search}%");
                });
            })->latest('id')->paginate($this->query->perPage);
    }
}
