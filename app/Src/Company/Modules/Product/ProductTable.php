<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Product;

use App\Models\Product;
use App\Utility\livewire\BaseTable;
use App\Utility\livewire\ExceptionTrait;
use App\Utility\livewire\TableForm;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ProductTable extends BaseTable
{
    use ExceptionTrait;

    public TableForm $query;

    public $canView;

    public $canCreate;

    public $canEdit;

    public $canDelete;

    protected ?string $moduleUniqueName = 'company.product';

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
        return view('company::Product.views.table', [
            'items' => $this->dataSource(),
        ])->layout('panel::layout.app', ['title' => str()->plural(__('company.products'))]);
    }

    public function delete(Product $product): void
    {
        $product->delete();
        flashAlert(__('app.panel.delete', ['name' => __('company.product')]), 'success');
    }

    protected function dataSource(): LengthAwarePaginator
    {
        return Product::select([
            'name',
            'id',
            'unit_id',
            'unit_name',
            'category_id',
            'category_name',
            'sku',
            'price',
            'product_id',
            'stock',
        ])->where('company_id', Auth::user()->company_id)->when($this->query->search, function (Builder $query) {
            return $query->where(function (Builder $query) {
                return $query->whereAny(['name', 'unit_name', 'category_name', 'sku', 'price', 'product_id', 'stock'], 'like', "%{$this->query->search}%");
            });
        })->latest('id')->paginate($this->query->perPage);
    }
}
