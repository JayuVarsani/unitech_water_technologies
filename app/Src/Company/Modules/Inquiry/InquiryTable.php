<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Inquiry;

use App\Models\Inquiry;
use App\Models\Material;
use App\Utility\livewire\BaseTable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Utility\livewire\ExceptionTrait;
use App\Utility\Enums\InquiryStatusEnum;
use App\Utility\livewire\TableForm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InquiryTable extends BaseTable
{
    use ExceptionTrait;

    use AuthorizesRequests;

    public TableForm $query;

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

    protected string $moduleUniqueName = 'company.inquiry';

    public function mount()
    {
        $this->canView = $this->hasPermission(type: 'view');
        $this->canCreate = $this->hasPermission(type: 'create');
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->canDelete = $this->hasPermission(type: 'delete');

        $this->materials = Material::where('company_id', Auth::user()->company_id)->get();
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function render(): View
    {
        return view('company::Inquiry.views.table', [
            'items' => $this->dataSource(),
            'user' => Auth::user(),
            'title' => __('app.panel.create_name', ['name' => __('company.inquiry')]),
        ])->layout('panel::layout.app', [
            'title' => 'Inquiries',
        ]);
    }

    public function changeStatus(Inquiry $inquiry, string $status, string $reason = null): void
    {
        $enumStatus = InquiryStatusEnum::tryFrom($status);
        if (!$enumStatus) {
            return;
        }

        $inquiry->update(['status' => $enumStatus->value, 'cancellation_reason' => $reason]);
        flashAlert(__('app.panel.update', ['name' => __('company.inquiry')]), 'success');
    }

    // public function delete(Inquiry $inquiry): void
    // {
    //     $inquiry->delete();
    //     flashAlert(__('app.panel.delete', ['name' => __('company.inquiry')]), 'success');
    // }

    protected function dataSource(): LengthAwarePaginator
    {
        return Inquiry::with('customer')
            ->where('company_id', Auth::user()->company_id)
            ->when($this->query->search, function (Builder $query) {
                $query->where(function (Builder $query) {
                    $query->where('id', 'like', "%{$this->query->search}%")
                        ->orWhere('date', 'like', "%{$this->query->search}%")
                        ->orWhereHas('customer', function (Builder $customerQuery) {
                            $customerQuery->where('name', 'like', "%{$this->query->search}%");
                        });
                });
            })
            ->latest('id')
            ->paginate($this->query->perPage);
    }
}
