<?php

declare(strict_types=1);

namespace App\Src\Admin\Modules\Company;

use App\Models\Company;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CompanyDetails extends Component
{
    public Company $company;

    public function mount(Company $company): void
    {
        $this->company = $company;

    }

    public function render(): View
    {
        $title = __('app.panel.details', ['name' => __('admin.company')]);

        return view('admin::Company.views.details', [
            'company' => $this->company,
            'title' => $title,
        ])->layout('panel::layout.app', [
            'breadcrumb' => [
                [str()->plural(__('admin.company')), route('admin.company.index')],
                [__('app.panel.details'), route('admin.company.details', $this->company->id)],
            ],
            'title' => $title,
        ]);
    }
}
