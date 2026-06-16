<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Visit;

use App\Models\Visit;
use App\Utility\livewire\BaseTable;
use App\Utility\livewire\ExceptionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class VisitTable extends BaseTable
{
    use ExceptionTrait;

    public VisitTableForm $query;

    public $canView;

    public $canCreate;

    public $canEdit;

    public $canDelete;

    protected array $resetQueryParams = ['query.search', 'query.perPage', 'query.startDate', 'query.endDate'];

    protected ?string $moduleUniqueName = 'company.visit';

    public function mount(): void
    {
        $this->canView = $this->hasPermission(type: 'view');
        $this->canCreate = $this->hasPermission(type: 'create');
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->canDelete = $this->hasPermission(type: 'delete');

        if (empty($this->query->startDate)) {
            $this->query->startDate = now()->startOfMonth()->format('Y-m-d');
        }

        if (empty($this->query->endDate)) {
            $this->query->endDate = now()->endOfMonth()->format('Y-m-d');
        }
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function render(): View
    {
        return view('company::Visit.views.table', [
            'items' => $this->dataSource(),
        ])->layout(
            'panel::layout.app',
            [
                'title' => 'Visits',
            ]
        );
    }

    public function delete(Visit $visit): void
    {
        abort_if($visit->company_id !== Auth::user()->company_id, 404);

        $visit->delete();
        flashAlert(__('app.panel.delete', ['name' => 'Visit']), 'success');
    }

    protected function dataSource(): LengthAwarePaginator
    {
        return Visit::select([
            'id',
            'visit_date',
            'site_name',
            'contact_person',
            'visit_number',
            'status',
            'sort_order',
        ])
            ->where('company_id', Auth::user()->company_id)
            ->when($this->query->startDate && $this->query->endDate, function (Builder $query) {
                $query->whereBetween('visit_date', [$this->query->startDate, $this->query->endDate]);
            })
            ->when($this->query->search, function (Builder $query) {
                $search = $this->query->search;

                $query->where(function (Builder $query) use ($search) {
                    $query->whereAny(
                        ['site_name', 'contact_person', 'visit_number', 'representative'],
                        'like',
                        "%{$search}%"
                    )->orWhereHas('amc', function (Builder $query) use ($search) {
                        $query->where('customer_name', 'like', "%{$search}%");
                    });
                });
            })
            ->latest('id')
            ->paginate($this->query->perPage);
    }
}
