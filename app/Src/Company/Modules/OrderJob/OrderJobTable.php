<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\OrderJob;

use App\Models\Material;
use App\Models\Order;
use App\Models\OrderJob;
use App\Models\OrderJobWastage;
use App\Utility\Enums\OrderJobStatusEnum;
use App\Utility\livewire\BaseTable;
use App\Utility\livewire\ExceptionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Renderless;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Src\Company\Modules\OrderJob\Form\OrderJobTableQueryForm;

class OrderJobTable extends BaseTable
{
    use ExceptionTrait;

    use AuthorizesRequests;

    public OrderJobTableQueryForm $query;

    protected array $resetQueryParams = [
        'query.search',
        'query.perPage',
        'query.status',
        'query.startDate',
        'query.endDate',
        'query.orderId'
    ];

    public $canView;

    public $canEdit;

    public $canCreateWastage;

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

    protected ?string $moduleUniqueName = 'company.orderjob';

    public function mount()
    {
        $this->canView = $this->hasPermission(type: 'view', moduleUniqueName: $this->moduleUniqueName);
        $this->canEdit = $this->hasPermission(type: 'edit', moduleUniqueName: $this->moduleUniqueName);
        $this->canCreateWastage = $this->hasPermission(type: 'create', moduleUniqueName: 'company.wastage');

        // $jobCardQuery = JobCard::with('media', 'customer')
        //     ->where('company_id', Auth::user()->company_id)
        //     ->where('job_status', JobCardStatusTypeEnum::Pending->value);

        // if (! empty($this->startDate) && ! empty($this->endDate)) {

        //     $jobCardQuery->whereBetween('job_date', [$this->startDate, $this->endDate]);
        // }
        // $this->jobcard = $jobCardQuery->get();

        $this->materials = Material::where('company_id', Auth::user()->company_id)->get();
    }

    public function hasPermission(string $type = 'view', bool $abort = true, ?string $moduleUniqueName = null): bool
    {
        return $moduleUniqueName ? auth()->user()->hasPermission($moduleUniqueName, $type, $abort) : true;
    }

    public function changeStatus($jobId, $status, $reason = null): void
    {
        $enumStatus = OrderJobStatusEnum::tryFrom($status);
        if (!$enumStatus) {
            return;
        }
        $val = [
            'status' => $enumStatus->value,
        ];
        if ($reason) {
            $val['cancellation_reason'] = $reason;
        }
        if ($enumStatus->value == OrderJobStatusEnum::Printed->value) {
            $val['print_by'] = Auth::user()->id;
        }
        if ($enumStatus->value == OrderJobStatusEnum::Design->value) {
            $val['design_by'] = Auth::user()->id;
        }
        OrderJob::find($jobId)?->update($val);
        flashAlert(__('app.panel.update', ['name' => __('company.orderjob')]), 'success');
    }
    public function render(): View
    {
        $companyId = Auth::user()->company_id;

        $scopedByOrder = function (Builder $q) use ($companyId): void {
            $q->where('company_id', $companyId);
            if (! empty($this->query->startDate) && ! empty($this->query->endDate)) {
                $q->whereBetween('date', [$this->query->startDate, $this->query->endDate]);
            }
            if (! empty($this->query->orderId)) {
                $q->where('order_id', $this->query->orderId);
            }
        };

        $queryPending = OrderJob::query()
            ->with(['order.customer'])
            ->where('status', OrderJobStatusEnum::Design->value)
            ->whereHas('order', $scopedByOrder);

        $queryCompleted = OrderJob::query()
            ->with(['order.customer'])
            ->where('status', OrderJobStatusEnum::Printed->value)
            ->whereHas('order', $scopedByOrder);
        $getJobData = $queryPending->get()->append('image_url');
        $getJobDataCom = $queryCompleted->get()->append('image_url');
        return view('company::OrderJob.views.table', [
            'items' => $this->dataSource(),
            'user' => Auth::user(),
            'orderjobdata' => $getJobData,
            'orderjobcom' => $getJobDataCom,
            // 'customer' => $getCustomer,
            'title' => __('app.panel.create_name', ['name' => __('company.orderjob')]),
        ])->layout('panel::layout.app', [
            'title' => 'Order Jobs',
        ]);
    }

    public function getPendingOrders()
    {
        $queryOrders = Order::query()
            ->with(['orderJobs'])
            ->where('company_id', Auth::user()->company_id)
            ->whereHas('orderJobs', function (Builder $query) {
                $query->where('status', OrderJobStatusEnum::Design->value);
            })
            ->when($this->query->startDate && $this->query->endDate, function (Builder $query) {
                $startDate = $this->query->startDate;
                $endDate = $this->query->endDate;
                if ($startDate && $endDate) {
                    return $query->whereBetween('date', [$startDate, $endDate]);
                }
            });
        return $queryOrders->get();
    }

    public function delete(OrderJob $orderjob): void
    {
        $orderjob->delete();
        flashAlert(__('app.panel.delete', ['name' => __('company.orderjob')]), 'success');
    }

    public function markAsPrinted($jobId): void
    {
        // dd($jobId);
        $job = OrderJob::find($jobId);
        if ($job) {
            $job->update(['status' => OrderJobStatusEnum::Printed->value, 'print_by' => Auth::user()->id]);
            // $this->jobcard = OrderJob::with('media', 'product')
            //     ->where('company_id', Auth::user()->company_id)
            //     ->where('status', OrderJobStatusEnum::Design->value)
            //     ->get();

            // $this->dispatch('job-updated', ['jobId' => $jobId]);
            flashAlert(__('job status updated'), 'success');
            //  redirect(request()->header('Referer'));
            redirect()->route('company.orderjob.index');
            // $this->dispatch('job-printed-success');
        } else {
            flashAlert(__('Job not found.'), 'danger');
        }
    }

    public function refreshJobList()
    {
        // Return the latest job list after an update
        $refreshJob = OrderJob::with(['order.customer'])
            ->where('company_id', Auth::user()->company_id);

        if (! empty($this->query->startDate) && ! empty($this->query->endDate)) {

            $refreshJob->whereBetween('job_date', [$this->query->startDate, $this->query->endDate]);
        }
        $jobdata = $refreshJob->get();

        return $jobdata;
    }

    #[Renderless]
    public function saveWastage($jobId)
    {

        $validatedData = $this->validate([
            'damageType' => 'required',
            'note' => 'required|string|max:255',
        ]);

        if ($this->damageType === 'whole_product') {

            OrderJobWastage::create([
                'order_job_id' => $jobId,
                'damage_type' => $this->damageType,
                'note' => $this->note,
            ]);
        } elseif ($this->damageType === 'materials' && ! empty($this->selectedMaterials)) {

            foreach ($this->selectedMaterials as $materialId) {
                OrderJobWastage::create([
                    'order_job_id' => $jobId,
                    'damage_type' => $this->damageType,
                    'note' => $this->note,
                    'material_id' => $materialId,
                    'height' => $this->dimensions[$materialId]['height'] ?? null,
                    'width' => $this->dimensions[$materialId]['width'] ?? null,
                ]);
            }
        }
        $this->redirectRoute('company.orderjob.index');
    }

    public function save(): void {}

    protected function dataSource(): LengthAwarePaginator
    {
        $companyId = Auth::user()->company_id;
        $search = $this->query->search;

        return OrderJob::query()
            ->with(['order.customer', 'designBy', 'printBy'])
            ->whereHas('order', fn(Builder $q) => $q->where('company_id', $companyId))
            ->when($search, function (Builder $query) use ($search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('job_no', 'like', "%{$search}%")
                        ->orWhere('product_name', 'like', "%{$search}%")
                        ->orWhereHas('order', fn(Builder $oq) => $oq->where('date', 'like', "%{$search}%"))
                        ->orWhereHas('order', fn(Builder $oq) => $oq->where('id', 'like', "%{$search}%"))
                        ->orWhereHas('order.customer', fn(Builder $cq) => $cq->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($this->query->status, function (Builder $query) use ($companyId) {
                $query->where('status', $this->query->status);
            })
            ->when($this->query->startDate && $this->query->endDate, function (Builder $query) {
                $startDate = $this->query->startDate;
                $endDate = $this->query->endDate;
                if ($startDate && $endDate) {
                    return $query->whereHas('order', fn(Builder $oq) => $oq->whereBetween('date', [$startDate, $endDate]));
                }
            })
            ->latest('id')
            ->paginate($this->query->perPage);
    }
}
