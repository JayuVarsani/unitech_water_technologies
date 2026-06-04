<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\JobCardMachine;

use App\Models\JobCard;
use App\Utility\livewire\BaseTable;
use App\Utility\livewire\ExceptionTrait;
use App\Utility\livewire\TableForm;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class JobCardMachineTable extends BaseTable
{
    use ExceptionTrait;

    public TableForm $query;

    public $canView;

    public $canCreate;

    public $canEdit;

    public $canDelete;

    protected ?string $moduleUniqueName = 'company.jobcard-machine';

    public function mount()
    {
        $this->canView = $this->hasPermission(type: 'view');
        $this->canCreate = $this->hasPermission(type: 'create');
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->canDelete = $this->hasPermission(type: 'delete');
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function render(): View
    {
        return view('company::JobCardMachine.views.table', [
            'items' => $this->dataSource(),
        ])->layout('panel::layout.app', ['title' => str()->plural(__('company.jobcard'))]);
    }

    public function delete(JobCard $jobcard): void
    {
        $jobcard->delete();
        flashAlert(__('app.panel.delete', ['name' => __('company.jobcard')]), 'success');
    }

    protected function dataSource(): LengthAwarePaginator
    {
        return JobCard::select([
            'job_no',
            'id',
            'job_date',
            'product_name',
            'customer_id',

        ])->with(['customer'])->where('company_id', Auth::user()->company_id)->when($this->query->search, function (Builder $query) {
            return $query->where(function (Builder $query) {
                $query->whereAny(['job_no', 'job_date', 'product_name'], 'like', "%{$this->query->search}%")
                // Search in the customer relationship
                    ->orWhereHas('customer', function (Builder $customerQuery) {
                        $customerQuery->where('name', 'like', "%{$this->query->search}%");
                    });
            });
        })->latest('id')->paginate($this->query->perPage);
    }
}
