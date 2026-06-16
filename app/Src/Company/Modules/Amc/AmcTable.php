<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Amc;

use App\Models\Amc;
use App\Utility\livewire\BaseTable;
use App\Utility\livewire\ExceptionTrait;
use App\Utility\livewire\TableForm;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class AmcTable extends BaseTable
{
    use ExceptionTrait;

    public TableForm $query;

    public $canView;

    public $canCreate;

    public $canEdit;

    public $canDelete;

    protected ?string $moduleUniqueName = 'company.amc';

    public function mount(): void
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
        return view('company::Amc.views.table', [
            'items' => $this->dataSource(),
        ])->layout('panel::layout.app', ['title' => 'AMC']);
    }

    public function delete(Amc $amc): void
    {
        abort_if($amc->company_id !== Auth::user()->company_id, 404);

        $amc->delete();
        flashAlert(__('app.panel.delete', ['name' => 'AMC']), 'success');
    }

    protected function dataSource(): LengthAwarePaginator
    {
        return Amc::select([
            'id',
            'customer_name',
            'from_date',
            'to_date',
            'visit_count',
        ])
            ->where('company_id', Auth::user()->company_id)
            ->when($this->query->search, function (Builder $query) {
                $query->where(function (Builder $query) {
                    $search = $this->query->search;

                    return $query->whereAny(['customer_name', 'id'], 'like', "%{$search}%")
                        ->orWhereDate('from_date', 'like', "%{$search}%")
                        ->orWhereDate('to_date', 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->paginate($this->query->perPage);
    }
}
