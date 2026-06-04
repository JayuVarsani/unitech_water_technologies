<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Wastage;

use App\Models\OrderJobWastage;
use App\Utility\livewire\BaseTable;
use App\Utility\livewire\ExceptionTrait;
use App\Utility\livewire\TableForm;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class WastageTable extends BaseTable
{
    use ExceptionTrait;

    public TableForm $query;

    public $canView;

    public $canDelete;

    protected ?string $moduleUniqueName = 'company.wastage';

    public function mount()
    {
        $this->canView = $this->hasPermission(type: 'view');
        $this->canDelete = $this->hasPermission(type: 'delete');
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function render(): View
    {
        return view('company::Wastage.views.table', [
            'items' => $this->dataSource(),
        ])->layout('panel::layout.app', ['title' => str()->plural(__('company.wastage'))]);
    }

    protected function dataSource(): LengthAwarePaginator
    {
        return OrderJobWastage::with(['orderJob.order', 'orderJob.printBy'])
            ->whereHas('orderJob.order', function (Builder $query) {
                $query->where('company_id', Auth::user()->company_id);
            })
            ->when($this->query->search, function (Builder $query) {
                $search = $this->query->search;

                $query->where(function (Builder $query) use ($search) {
                    $query
                        ->orWhere('id', 'like', "%{$search}%")
                        ->orWhere('damage_type', 'like', "%{$search}%")
                        ->orWhere('note', 'like', "%{$search}%")
                        ->orWhereHas('orderJob', function (Builder $q) use ($search) {
                            $q->where('job_no', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('id')
            ->paginate($this->query->perPage);
    }
}
