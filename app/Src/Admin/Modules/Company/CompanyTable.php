<?php

declare(strict_types=1);

namespace App\Src\Admin\Modules\Company;

use App\Models\Company;
use App\Utility\livewire\BaseTable;
use App\Utility\livewire\ExceptionTrait;
use App\Utility\livewire\TableForm;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyTable extends BaseTable
{
    use ExceptionTrait;

    public TableForm $query;

    public function render(): View
    {
        return view('admin::Company.views.table', [
            'items' => $this->dataSource(),
        ])->layout('panel::layout.app', ['title' => str()->plural(__('admin.companies'))]);
    }

    public function delete(Company $company): void
    {
        $company->delete();
        flashAlert(__('app.panel.delete', ['name' => __('admin.company')]), 'success');
    }

    protected function dataSource(): LengthAwarePaginator
    {
        return Company::select(['name', 'id', 'gst_no', 'status'])->with('company_staff')->when($this->query->search, function (Builder $query) {
            return $query->where(function (Builder $query) {
                return $query->whereAny(['name', 'id', 'gst_no', 'status'], 'like', "%{$this->query->search}%");
            });
        })->latest('id')->paginate($this->query->perPage);
    }
}
