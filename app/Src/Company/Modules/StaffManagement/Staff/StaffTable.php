<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\StaffManagement\Staff;

use App\Models\Staff;
use App\Utility\Enums\CompanyTypeEnum;
use App\Utility\livewire\BaseTable;
use App\Utility\livewire\ExceptionTrait;
use App\Utility\livewire\TableForm;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class StaffTable extends BaseTable
{
    use ExceptionTrait;

    public TableForm $query;

    public $canView;

    public $canCreate;

    public $canEdit;

    public $canDelete;

    protected ?string $moduleUniqueName = 'company.staff';

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
        return view('company::StaffManagement.Staff.views.table', [
            'items' => $this->dataSource(),
        ])->layout('panel::layout.app', ['title' => (__('company.staff-management.staff'))]);
    }

    public function delete(Staff $staff): void
    {
        $staff->delete();
        flashAlert(__('app.panel.delete', ['name' => __('company.staff-management.staff')]), 'success');
    }

    protected function dataSource(): LengthAwarePaginator
    {
        return Staff::select([
            'name',
            'email',
            'id',
            'contact_number',
            'address',
            'staff_role_name',
            'joining_date',
            'status',
        ])
            ->where('company_id', Auth::user()->company_id)
            ->where('type', '!=', CompanyTypeEnum::Admin->value)
            ->when($this->query->search, function (Builder $query) {
                return $query->where(function (Builder $query) {
                    return $query->whereAny(['name', 'email', 'id', 'contact_number', 'address', 'staff_role_name', 'joining_date'], 'like', "%{$this->query->search}%");
                });
            })->latest('id')->paginate($this->query->perPage);
    }
}
