<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Category;

use App\Src\Company\Modules\Category\Form\CategoryForm;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreateCategory extends Component
{
    public CategoryForm $form;

    public $canCreate;

    public string $redirectTo = '';

    protected ?string $moduleUniqueName = 'company.category';

    public function mount()
    {
        $this->canCreate = $this->hasPermission(type: 'create');
        $this->redirectTo = request('redirect_to', '');
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->createCategory();

        flashAlert(__('app.panel.store', ['name' => __('company.category')]), 'success');
        if ($this->redirectTo) {

            redirect($this->redirectTo);
        } else {
            $this->redirectRoute('company.category.index');
        }

    }

    public function render(): View
    {
        $title = __('app.panel.create_name', ['name' => __('company.category')]);

        if ($this->canCreate) {
            return view('company::Category.views.form', [
                'title' => $title,

            ])->layout(
                'panel::layout.app',
                [
                    'breadcrumb' => [
                        [str()->plural(__('company.category')), route('company.category.index')],
                        [__('app.panel.create'), route('company.category.create')],
                    ],
                    'title' => $title,
                ]
            );
        } else {
            abort(403);
        }
    }
}
