<?php

declare(strict_types=1);

namespace App\Src\Admin\Modules\Company;

use App\Src\Admin\Modules\Company\Form\CompanyForm;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCompany extends Component
{
    use WithFileUploads;

    public CompanyForm $form;

    public function save(): void
    {
        $this->form->createCompany();

        flashAlert(__('app.panel.store', ['name' => __('admin.company')]), 'success');
        $this->redirectRoute('admin.company.index');
    }

    public function render(): View
    {
        $title = __('app.panel.create_name', ['name' => __('admin.company')]);

        return view('admin::Company.views.form', [
            'title' => $title,
            'isEdit' => false,
            'existingLogo' => '',
        ])->layout('panel::layout.app', [
            'breadcrumb' => [
                [str()->plural(__('admin.company')), route('admin.company.index')],
                [__('app.panel.create'), route('admin.company.create')],
            ],
            'title' => $title,
        ]
        );
    }
}
