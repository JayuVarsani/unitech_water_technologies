<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\TreatmentScheme;

use App\Models\TreatmentScheme;
use App\Utility\livewire\BaseTable;
use App\Utility\livewire\ExceptionTrait;
use App\Utility\livewire\TableForm;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class TreatmentSchemeTable extends BaseTable
{
    use ExceptionTrait;

    public TableForm $query;

    public $canView;

    public $canCreate;

    public $canEdit;

    public $canDelete;

    protected ?string $moduleUniqueName = 'company.treatment-scheme';

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
        return view('company::TreatmentScheme.views.table', [
            'items' => $this->dataSource(),
        ])->layout('panel::layout.app', ['title' => 'Treatment Schemes']);
    }

    public function delete(TreatmentScheme $treatmentScheme): void
    {
        $treatmentScheme->delete();
        flashAlert(__('app.panel.delete', ['name' => 'Treatment Scheme']), 'success');
    }

    protected function dataSource(): LengthAwarePaginator
    {
        return TreatmentScheme::select([
            'id',
            'name',
        ])
            ->where('company_id', Auth::user()->company_id)
            ->when($this->query->search, function (Builder $query) {
                $query->where(function (Builder $query) {
                    $search = $this->query->search;

                    return $query->whereAny(['name', 'id'], 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->paginate($this->query->perPage);
    }
}
