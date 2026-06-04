<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Profile;

use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\Inquiry;
use App\Models\JobCard;
use App\Models\Material;
use App\Models\Order;
use App\Models\OrderJob;
use App\Models\Product;
use App\Models\Staff;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;

class Dashboard extends Component
{
    public $staffCount;

    public $productCount;

    public $materialCount;

    public $customerCount;

    public $customergroupCount;

    public $orderCount;

    public $inquiryCount;

    public $orderJobCount;

    public $canViewOrder;

    public $canViewOrderJob;

    public $canViewInquiry;

    public $canViewCustomer;

    public $canViewCustomerGroup;

    public $canViewProduct;

    public $canViewStaff;

    public $canViewMaterial;

    protected ?string $moduleUniqueNameCustomer = 'company.customer';

    protected ?string $moduleUniqueNameCustomerGroup = 'company.customer-group';

    protected ?string $moduleUniqueNameProduct = 'company.product';

    protected ?string $moduleUniqueNameStaff = 'company.staff';

    protected ?string $moduleUniqueNameMaterial = 'company.material';

    protected ?string $moduleUniqueNameOrder = 'company.order';

    protected ?string $moduleUniqueNameOrderJob = 'company.orderjob';

    protected ?string $moduleUniqueNameInquiry = 'company.inquiry';

    public function mount()
    {
        $this->canViewCustomer = $this->hasPermissionCustomer(type: 'view');
        $this->canViewCustomerGroup = $this->hasPermissionCustomerGroup(type: 'view');
        $this->canViewProduct = $this->hasPermissionProduct(type: 'view');
        $this->canViewStaff = $this->hasPermissionStaff(type: 'view');
        $this->canViewMaterial = $this->hasPermissionMaterial(type: 'view');
        $this->canViewOrder = $this->hasPermissionOrder(type: 'view');
        $this->canViewOrderJob = $this->hasPermissionOrderJob(type: 'view');
        $this->canViewInquiry = $this->hasPermissionInquiry(type: 'view');

        $this->orderCount = Order::toBase()->selectRaw("
        COUNT(*) as total_count,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN status = 'printed' THEN 1 ELSE 0 END) as printed,
        SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
        SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
    ")->where('company_id', Auth::user()->company_id)->first();

        $this->orderJobCount = OrderJob::toBase()
            ->selectRaw("
        COUNT(*) as total_count,
        SUM(CASE WHEN order_jobs.status = 'completed' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN order_jobs.status = 'printed' THEN 1 ELSE 0 END) as printed,
        SUM(CASE WHEN order_jobs.status = 'design' THEN 1 ELSE 0 END) as design,
        SUM(CASE WHEN order_jobs.status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
    ")
            ->join('orders', 'orders.id', '=', 'order_jobs.order_id')
            ->where('orders.company_id', Auth::user()->company_id)
            ->first();

        $this->inquiryCount = Inquiry::toBase()
            ->selectRaw("
        COUNT(*) as total_count,
        SUM(CASE WHEN inquiries.status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
        SUM(CASE WHEN inquiries.status = 'open' THEN 1 ELSE 0 END) as open,
        SUM(CASE WHEN inquiries.status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
    ")
            ->join('customers', 'customers.id', '=', 'inquiries.customer_id')
            ->where('customers.company_id', Auth::user()->company_id)
            ->first();

        $this->productCount = Product::where('company_id', Auth::user()->company_id)->count();
        $this->staffCount = Staff::where('company_id', Auth::user()->company_id)->where('type', 'staff')->count();
        $this->materialCount = Material::where('company_id', Auth::user()->company_id)->count();
        $this->customerCount = Customer::where('company_id', Auth::user()->company_id)->count();
        $this->customergroupCount = CustomerGroup::where('company_id', Auth::user()->company_id)->count();
    }

    public function hasPermissionCustomer(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueNameCustomer ? auth()->user()->hasPermission($this->moduleUniqueNameCustomer, $type, $abort) : true;
    }

    public function hasPermissionCustomerGroup(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueNameCustomerGroup ? auth()->user()->hasPermission($this->moduleUniqueNameCustomerGroup, $type, $abort) : true;
    }

    public function hasPermissionProduct(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueNameProduct ? auth()->user()->hasPermission($this->moduleUniqueNameProduct, $type, $abort) : true;
    }

    public function hasPermissionStaff(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueNameStaff ? auth()->user()->hasPermission($this->moduleUniqueNameStaff, $type, $abort) : true;
    }

    public function hasPermissionMaterial(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueNameMaterial ? auth()->user()->hasPermission($this->moduleUniqueNameMaterial, $type, $abort) : true;
    }

    public function hasPermissionOrder(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueNameOrder ? auth()->user()->hasPermission($this->moduleUniqueNameOrder, $type, $abort) : true;
    }

    public function hasPermissionOrderJob(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueNameOrderJob ? auth()->user()->hasPermission($this->moduleUniqueNameOrderJob, $type, $abort) : true;
    }

    public function hasPermissionInquiry(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueNameInquiry ? auth()->user()->hasPermission($this->moduleUniqueNameInquiry, $type, $abort) : true;
    }

    public function render(): View
    {

        return view('company::Profile.views.dashboard')
            ->layout('panel::layout.app', [
                'title' => __('company.dashboard'),
            ]);
    }
}
