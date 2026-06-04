<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Order;

use App\Models\Order;
use App\Models\Material;
use App\Utility\livewire\BaseTable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Utility\livewire\ExceptionTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Src\Company\Modules\Order\Form\OrderTableQueryForm;

class OrderTable extends BaseTable
{
    use ExceptionTrait;

    use AuthorizesRequests;

    public OrderTableQueryForm $query;

    protected array $resetQueryParams = [
        'query.search',
        'query.perPage',
        'query.status',
        'query.startDate',
        'query.endDate'
    ];

    public $canView;

    public $canEdit;

    public $canDelete;

    public $jobcard;

    public $damageType;

    public $note;

    public $materials = [];

    public $selectedMaterials = [];

    public $dimensions = [];

    public $selectedJobId;

    public $jobId;

    public $startDate;

    public $endDate;

    protected ?string $moduleUniqueName = 'company.order';

    public function mount()
    {
        $this->canView = $this->hasPermission(type: 'view');
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->canDelete = $this->hasPermission(type: 'delete');

        $this->materials = Material::where('company_id', Auth::user()->company_id)->get();

    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    // public function filterJobs($startDate, $endDate)
    // {

    //     $getJobData = JobCard::with('media', 'customer')
    //     ->where('company_id', Auth::user()->company_id)
    //     ->where('job_status', JobCardStatusTypeEnum::Pending->value)
    //     ->whereBetween('job_date', [$startDate, $endDate])->get();

    //     $getJobDataCom = JobCard::with('media', 'customer')
    //     ->where('company_id', Auth::user()->company_id)
    //     ->where('job_status', JobCardStatusTypeEnum::Completed->value)
    //     ->whereBetween('job_date', [$startDate, $endDate])->get();

    //      $this->dispatch('jobsFiltered', $getJobData, $getJobDataCom);
    // }

    public function render(): View
    {
        return view('company::Order.views.table', [
            'items' => $this->dataSource(),
            'user' => Auth::user(),
            'title' => __('app.panel.create_name', ['name' => __('company.order')]),
        ])->layout('panel::layout.app', [
            'title' => __('app.panel.orders'),
        ]);
    }

    // public function delete(Order $order): void
    // {
    //     $order->delete();
    //     flashAlert(__('app.panel.delete', ['name' => __('company.order')]), 'success');
    // }

    protected function dataSource(): LengthAwarePaginator
    {
        return Order::query()
            ->with(['customer', 'orderJobs'])
            ->where('company_id', Auth::user()->company_id)
            ->when($this->query->search, function (Builder $query) {
                $query->where(function (Builder $query) {
                    $query->where('id', 'like', "%{$this->query->search}%")
                        ->orWhere('date', 'like', "%{$this->query->search}%")
                        ->orWhereHas('customer', function (Builder $customerQuery) {
                            $customerQuery->where('name', 'like', "%{$this->query->search}%");
                        // })
                        // ->orWhereHas('orderJobs', function (Builder $orderJobQuery) {
                        //     $orderJobQuery->where('job_no', 'like', "%{$this->query->search}%");
                        });
                });
            })
            ->when($this->query->status, function (Builder $query) {
                $query->where('status', $this->query->status);
            })
            ->when($this->query->startDate && $this->query->endDate, function (Builder $query) {
                $query->whereBetween('date', [$this->query->startDate, $this->query->endDate]);
            })
            ->latest('id')
            ->paginate($this->query->perPage);
    }
}
