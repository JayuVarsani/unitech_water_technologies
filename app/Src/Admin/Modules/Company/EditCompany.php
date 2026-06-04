<?php

declare(strict_types=1);

namespace App\Src\Admin\Modules\Company;

use App\Models\Company;
use App\Src\Admin\Modules\Company\Form\CompanyForm;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditCompany extends Component
{
    use WithFileUploads;

    #[Locked]
    public Company $company;

    public CompanyForm $form;

    public function mount(Company $company): void
    {
        $this->company = $company;

        $this->form->setCompany($company);
    }

    public function save(): void
    {
        $this->form->update($this->company);
        flashAlert(__('app.panel.update', ['name' => __('admin.company')]), 'success');
        $this->redirectRoute('admin.company.index');
    }

    public function render(): View
    {
        $title = __('app.panel.edit_name', ['name' => __('admin.company')]);

        return view('admin::Company.views.form', ['title' => $title,
            'isEdit' => true,
            'existingLogo' => $this->company->getfirstMediaUrl('company_logo'),

        ])
            ->layout('panel::layout.app', [
                'breadcrumb' => [
                    [str()->plural(__('admin.company')), route('admin.company.index')],
                    [__('app.panel.edit'), route('admin.company.edit', $this->company->id)],
                ],
                'title' => $title,
            ]);
    }
}
