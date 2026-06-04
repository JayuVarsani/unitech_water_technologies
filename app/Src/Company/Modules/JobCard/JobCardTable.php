<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\JobCard;

use App\Models\Customer;
use App\Models\JobCard;
use App\Models\JobWastage;
use App\Models\Material;
use App\Src\Company\Modules\JobCard\Form\JobCardTableQueryForm;
use App\Utility\Enums\JobCardStatusTypeEnum;
use App\Utility\livewire\BaseTable;
use App\Utility\livewire\ExceptionTrait;
use App\Utility\livewire\TableForm;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Renderless;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class JobCardTable extends BaseTable
{
    use ExceptionTrait;

    use AuthorizesRequests;

    // public TableForm $query;

    public JobCardTableQueryForm $query;

    public $canView;

    public $canCreate;

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

    protected ?string $moduleUniqueName = 'company.jobcard';

    public function mount()
    {

        $this->canView = $this->hasPermission(type: 'view');
        $this->canCreate = $this->hasPermission(type: 'create');
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->canDelete = $this->hasPermission(type: 'delete');

        // $jobCardQuery = JobCard::with('media', 'customer')
        //     ->where('company_id', Auth::user()->company_id)
        //     ->where('job_status', JobCardStatusTypeEnum::Pending->value);

        // if (! empty($this->startDate) && ! empty($this->endDate)) {

        //     $jobCardQuery->whereBetween('job_date', [$this->startDate, $this->endDate]);
        // }
        // $this->jobcard = $jobCardQuery->get();

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

        $queryPending = JobCard::with('media', 'customer')
            ->where('company_id', Auth::user()->company_id)
            ->where('job_status', JobCardStatusTypeEnum::Pending->value);

        $queryCompleted = JobCard::with('media', 'customer')
            ->where('company_id', Auth::user()->company_id)
            ->where('job_status', JobCardStatusTypeEnum::Printed->value);

        if (! empty($this->query->startDate) && ! empty($this->query->endDate)) {

            $queryPending->whereBetween('job_date', [
                $this->query->startDate,
                $this->query->endDate,
            ]);

            $queryCompleted->whereBetween('job_date', [
                $this->query->startDate,
                $this->query->endDate,
            ]);
        }

        $getJobData = $queryPending->get()->append('image_url');
        $getJobDataCom = $queryCompleted->get()->append('image_url');

        $getCustomer = Customer::where('company_id', Auth::user()->company_id)->get();

        return view('company::JobCard.views.table', [
            'items' => $this->dataSource(),
            'user' => Auth::user(),
            'jobcarddata' => $getJobData,
            'jobcardcom' => $getJobDataCom,
            'customer' => $getCustomer,
            'title' => __('app.panel.create_name', ['name' => __('company.jobcard')]),
        ])->layout('panel::layout.app', [
            'title' => 'Job Cards',
        ]);
    }

    public function delete(JobCard $jobcard): void
    {
        $this->authorize('delete', $jobcard);
        $jobcard->delete();
        flashAlert(__('app.panel.delete', ['name' => __('company.jobcard')]), 'success');
    }

    public function markAsPrinted($jobId): void
    {

        $job = JobCard::find($jobId);

        if ($job) {
            $job->update(['job_status' => JobCardStatusTypeEnum::Printed->value, 'staff_id' => Auth::user()->id]);
            $this->jobcard = JobCard::with('media', 'customer')
                ->where('company_id', Auth::user()->company_id)
                ->where('job_status', JobCardStatusTypeEnum::Pending->value)
                ->get();

            $this->dispatch('job-updated', ['jobId' => $jobId]);
            flashAlert(__('jobcard status updated'), 'success');
            //  redirect(request()->header('Referer'));
            redirect()->route('company.jobcard.index');
            // $this->dispatch('job-printed-success');
        } else {
            session()->flash('error', __('Job not found.'));
        }
    }

    public function refreshJobList()
    {
        // Return the latest job list after an update
        $refreshJob = JobCard::with('media', 'customer')
            ->where('company_id', Auth::user()->company_id)
            ->where('job_status', JobCardStatusTypeEnum::Pending->value);

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

        // // Additional validation for 'materials' type
        // if ($this->damageType === 'materials') {
        //     $rules['selectedMaterials'] = 'required|array|min:1';

        //     foreach ($this->selectedMaterials as $materialId) {
        //         $rules["dimensions.$materialId.height"] = 'required|numeric|min:1';
        //         $rules["dimensions.$materialId.width"] = 'required|numeric|min:1';
        //     }
        // }

        // $this->validate($rules);
        // try
        // {
        if ($this->damageType === 'whole_product') {

            JobWastage::create([
                'job_id' => $jobId,
                'damage_type' => $this->damageType,
                'note' => $this->note,
            ]);
        } elseif ($this->damageType === 'materials' && ! empty($this->selectedMaterials)) {

            foreach ($this->selectedMaterials as $materialId) {
                JobWastage::create([
                    'job_id' => $jobId,
                    'damage_type' => $this->damageType,
                    'note' => $this->note,
                    'material_id' => $materialId,
                    'height' => $this->dimensions[$materialId]['height'] ?? null,
                    'width' => $this->dimensions[$materialId]['width'] ?? null,
                ]);
            }
        }
        // return ['success' => true, 'message' => 'Saved successfully'];
        // }catch (Exception $exception) {
        //     // return ['success' => false, 'message' => $exception->getMessage()];
        // }

        $this->redirectRoute('company.jobcard.index');
    }

    public function save(): void {}

    protected function dataSource(): LengthAwarePaginator
    {

        return JobCard::select([
            'job_no',
            'id',
            'job_date',
            'product_name',
            'job_status',
            'customer_id',
        ])
            ->with(['customer'])
            ->where('company_id', Auth::user()->company_id)
            ->where('status', '1')
            ->when($this->query->job_status, fn(Builder $query) => $query->where('job_status', $this->query->job_status))
            ->when($this->query->customer_id_filter, fn(Builder $query) => $query->where('customer_id', $this->query->customer_id_filter))
            // Apply search filter
            ->when($this->query->search, function (Builder $query) {
                $query->where(function (Builder $query) {
                    $query->where('job_no', 'like', "%{$this->query->search}%")
                        ->orWhere('job_date', 'like', "%{$this->query->search}%")
                        ->orWhere('job_status', 'like', "%{$this->query->search}%")
                        ->orWhere('product_name', 'like', "%{$this->query->search}%")
                        ->orWhereHas('customer', function (Builder $customerQuery) {
                            $customerQuery->where('name', 'like', "%{$this->query->search}%");
                        });
                });
            })
            ->when($this->query->startDate && $this->query->endDate, function (Builder $query) {
                $startDate = $this->query->startDate;
                $endDate = $this->query->endDate;

                if ($startDate && $endDate) {
                    $query->whereBetween('job_date', [$startDate, $endDate]);
                }
            })
            ->latest('id')
            ->paginate($this->query->perPage);
    }
}
