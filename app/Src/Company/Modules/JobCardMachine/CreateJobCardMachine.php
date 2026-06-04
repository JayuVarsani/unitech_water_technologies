<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\JobCardMachine;

use App\Models\JobCard;
use App\Models\JobWastage;
use App\Models\Material;
use App\Src\Company\Modules\JobCardMachine\Form\JobCardMachineForm;
use App\Utility\Enums\JobCardStatusTypeEnum;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateJobCardMachine extends Component
{
    use WithFileUploads;

    public JobCardMachineForm $form;

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

    protected ?string $moduleUniqueName = 'company.jobcard-machine';

    public function mount()
    {
        $this->canCreate = $this->hasPermission(type: 'create');
        $jobCardQuery = JobCard::with('media', 'customer')
            ->where('company_id', Auth::user()->company_id)
            ->where('job_status', JobCardStatusTypeEnum::Pending->value);

        if (! empty($this->startDate) && ! empty($this->endDate)) {
            $jobCardQuery->whereBetween('job_date', [$this->startDate, $this->endDate]);
        }
        $this->jobcard = $jobCardQuery->get();
        $this->materials = Material::where('company_id', Auth::user()->company_id)->get();

    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        // $jobdata = $this->form->createJobcard();
        // $job_status = $jobdata->job_status;
        // $this->dispatch('jobCardAdded', $jobdata->customer_id);

        // flashAlert(__('app.panel.store', ['name' => __('company.jobcard')]), 'success');
        // if ($job_status === 'completed') {
        //     $this->redirectRoute('company.jobcard.index');
        // }

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

            // // $this->jobcard = $this->jobcard->reject(fn($j) => $j->id === $jobId);
            $this->dispatch('job-updated', ['jobId' => $jobId]);
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

        if (! empty($this->startDate) && ! empty($this->endDate)) {

            $refreshJob->whereBetween('job_date', [$this->startDate, $this->endDate]);
        }
        $jobdata = $refreshJob->get();

        return $jobdata;
    }

    public function saveWastage()
    {

        $this->validate([
            'damageType' => 'required',
            'note' => 'nullable|string|max:255',
        ]);

        if ($this->damageType === 'whole_product') {

            JobWastage::create([
                'job_id' => $this->jobId,
                'damage_type' => $this->damageType,
                'note' => $this->note,
            ]);
        } elseif ($this->damageType === 'materials' && ! empty($this->selectedMaterials)) {

            foreach ($this->selectedMaterials as $materialId) {
                JobWastage::create([
                    'job_id' => $this->jobId,
                    'damage_type' => $this->damageType,
                    'note' => $this->note,
                    'material_id' => $materialId,
                    'height' => $this->dimensions[$materialId]['height'] ?? null,
                    'width' => $this->dimensions[$materialId]['width'] ?? null,
                ]);
            }
        }

        // $this->reset(['damageType', 'note', 'selectedMaterials', 'dimensions', 'materials']);
        // $this->dispatch('alert', ['type' => 'success', 'message' => 'Wastage reported successfully.']);
        // $this->dispatch('hide-wastage-modal');

        $this->redirectRoute('company.jobcard-machine.create');
    }

    public function render(): View
    {

        $title = __('app.panel.create_name', ['name' => __('company.jobcard')]);
        $queryPending = JobCard::with('media', 'customer')
            ->where('company_id', Auth::user()->company_id)
            ->where('job_status', JobCardStatusTypeEnum::Pending->value);

        // Base query for completed job cards
        $queryCompleted = JobCard::with('media', 'customer')
            ->where('company_id', Auth::user()->company_id)
            ->where('job_status', JobCardStatusTypeEnum::Completed->value);

        // Check if startDate and endDate are set, then apply the date range filter
        if ($this->startDate && $this->endDate) {

            $queryPending->whereBetween('job_date', [$this->startDate, $this->endDate]);
            $queryCompleted->whereBetween('job_date', [$this->startDate, $this->endDate]);
        }

        // Fetch the filtered data
        $getJobData = $queryPending->get();
        $getJobDataCom = $queryCompleted->get();
        // $getJobData = JobCard::with('media', 'customer')->where('company_id', Auth::user()->company_id)->where('job_status', JobCardStatusTypeEnum::Pending->value)->get();
        // $getJobDataCom = JobCard::with('media', 'customer')->where('company_id', Auth::user()->company_id)->where('job_status', JobCardStatusTypeEnum::Completed->value)->get();

        if ($this->canCreate) {
            return view('company::JobCardMachine.views.form', [
                'title' => $title,
                'jobcard' => $getJobData,
                'jobcardcom' => $getJobDataCom,

            ])->layout(
                'panel::layout.app',
                [
                    'breadcrumb' => [
                        [str()->plural(__('company.jobcard')), route('company.jobcard-machine.index')],
                        [__('app.panel.create'), route('company.jobcard-machine.create')],
                    ],
                    'title' => $title,
                ]
            );
        } else {
            abort(403);
        }
    }
}
