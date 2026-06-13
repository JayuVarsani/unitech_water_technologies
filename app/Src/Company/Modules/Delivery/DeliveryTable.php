<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Delivery;

use App\Models\Delivery;
use App\Utility\livewire\BaseTable;
use App\Utility\livewire\ExceptionTrait;
use App\Utility\livewire\TableForm;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class DeliveryTable extends BaseTable
{
    use ExceptionTrait;

    public TableForm $query;

    public $canView;

    public $canCreate;

    public $canEdit;

    public $canDelete;

    protected ?string $moduleUniqueName = 'company.delivery';

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
        return view('company::Delivery.views.table', [
            'items' => $this->dataSource(),
        ])->layout('panel::layout.app', ['title' => 'Deliveries']);
    }

    public function delete(Delivery $delivery): void
    {
        abort_if($delivery->company_id !== Auth::user()->company_id, 404);

        $delivery->delete();
        flashAlert(__('app.panel.delete', ['name' => 'Delivery']), 'success');
    }

    protected function dataSource(): LengthAwarePaginator
    {
        return Delivery::select([
            'id',
            'delivery_date',
            'customer_name',
            'final_amount',
        ])
            ->where('company_id', Auth::user()->company_id)
            ->when($this->query->search, function (Builder $query) {
                $query->where(function (Builder $query) {
                    $search = $this->query->search;

                    return $query->whereAny(
                        ['customer_name', 'item_details'],
                        'like',
                        "%{$search}%"
                    );
                });
            })
            ->latest('id')
            ->paginate($this->query->perPage);
    }
}
