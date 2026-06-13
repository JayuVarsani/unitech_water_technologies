<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Profile;

use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\Delivery;
use App\Models\Staff;
use App\Models\Visit;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $visitCount;

    public $deliveryCount;

    public $customerCount;

    public $customergroupCount;

    public $staffCount;

    public $canViewVisit;

    public $canViewDelivery;

    public $canViewCustomer;

    public $canViewCustomerGroup;

    public $canViewStaff;

    protected ?string $moduleUniqueNameVisit = 'company.visit';

    protected ?string $moduleUniqueNameDelivery = 'company.delivery';

    protected ?string $moduleUniqueNameCustomer = 'company.customer';

    protected ?string $moduleUniqueNameCustomerGroup = 'company.customer-group';

    protected ?string $moduleUniqueNameStaff = 'company.staff';

    public function mount(): void
    {
        $companyId = Auth::user()->company_id;

        $this->canViewVisit = $this->hasPermission($this->moduleUniqueNameVisit);
        $this->canViewDelivery = $this->hasPermission($this->moduleUniqueNameDelivery);
        $this->canViewCustomer = $this->hasPermission($this->moduleUniqueNameCustomer);
        $this->canViewCustomerGroup = $this->hasPermission($this->moduleUniqueNameCustomerGroup);
        $this->canViewStaff = $this->hasPermission($this->moduleUniqueNameStaff);

        $this->visitCount = Visit::where('company_id', $companyId)->count();

        $this->deliveryCount = Delivery::query()
            ->where('company_id', $companyId)
            ->toBase()
            ->selectRaw("
                COUNT(*) as total_count,
                SUM(CASE WHEN entry_type = 'form' THEN 1 ELSE 0 END) as direct,
                SUM(CASE WHEN entry_type = 'image' THEN 1 ELSE 0 END) as transport
            ")
            ->first();

        $this->customerCount = Customer::where('company_id', $companyId)->count();
        $this->customergroupCount = CustomerGroup::where('company_id', $companyId)->count();
        $this->staffCount = Staff::where('company_id', $companyId)->where('type', 'staff')->count();
    }

    public function hasPermission(?string $moduleUniqueName, string $type = 'view'): bool
    {
        return $moduleUniqueName ? auth()->user()->hasPermission($moduleUniqueName, $type, false) : true;
    }

    public function render(): View
    {
        return view('company::Profile.views.dashboard')
            ->layout('panel::layout.app', [
                'title' => __('company.dashboard'),
            ]);
    }
}
