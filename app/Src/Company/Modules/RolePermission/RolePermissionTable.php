<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\RolePermission;

use App\Models\Role;
use App\Utility\livewire\TableForm;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RolePermissionTable extends Component
{
    public TableForm $query;

    public function render(): View
    {
        return view('company::RolePermission.views.role-table', ['items' => $this->dataSource()])
            ->layout('panel::layout.app', [
                'title' => __('company.role-permission.title'),
                'breadcrumb' => [[__('company.role-permission.title'), route('company.staff-management.role-permission.index')]],
            ]);
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        flashAlert(__('app.panel.delete', ['name' => __('company.role-permission.title')]), 'success');
        $this->redirectRoute('company.staff-management.role-permission.index');
    }

    private function dataSource(): LengthAwarePaginator
    {
        [$search] = [$this->query->search];

        return Role::query()->when($search, function (Builder $query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })->where('company_id', Auth::user()->company_id)->latest()->paginate($this->query->perPage);
    }
}
