<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\CustomerManagement\Customer;

use Livewire\Component;
use App\Models\Customer;
use Illuminate\Contracts\View\View;

class ViewCustomer extends Component
{
    public Customer $customer;

    public $canView;

    public $canEdit;

    protected ?string $moduleUniqueName = 'company.customer';

    public function mount(Customer $customer): void
    {
        $this->customer = $customer;
        $this->customer->load('products', 'orders.orderJobs');

        $this->canView = $this->hasPermission(type: 'view');
        $this->canEdit = $this->hasPermission(type: 'edit');
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function render(): View
    {
        $title = __('app.panel.view_name', ['name' => __('company.customer-management.customer')]);
        if ($this->canView) {
            return view(
                'company::CustomerManagement.Customer.views.view',
                ['title' => $title])
                ->layout('panel::layout.app', [
                    'breadcrumb' => [
                        [str()->plural(__('company.customer-management.customer')), route('company.customer-management.customer.index')],
                        [__('app.panel.view'), route('company.customer-management.customer.view', $this->customer->id)],
                    ],
                    'title' => $title,
                ]);
        } else {
            abort(403);
        }
    }
}
