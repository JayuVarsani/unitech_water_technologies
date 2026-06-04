<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Category;

use App\Models\Category;
use App\Src\Company\Modules\Category\Form\CategoryForm;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditCategory extends Component
{
    #[Locked]
    public Category $category;

    public CategoryForm $form;

    public $canEdit;

    protected ?string $moduleUniqueName = 'company.category';

    public function mount(Category $category): void
    {
        $this->canEdit = $this->hasPermission(type: 'edit');
        $this->category = $category;
        $this->form->setCategory($category);
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function save(): void
    {
        $this->form->update($this->category);
        flashAlert(__('app.panel.update', ['name' => __('company.category')]), 'success');
        $this->redirectRoute('company.category.index');
    }

    public function render(): View
    {
        $title = __('app.panel.edit_name', ['name' => __('company.category')]);

        if ($this->canEdit) {
            return view('company::Category.views.form', [
                'title' => $title,

            ])
                ->layout('panel::layout.app', [
                    'breadcrumb' => [
                        [str()->plural(__('company.category')), route('company.category.index')],
                        [__('app.panel.edit'), route('company.category.edit', $this->category->id)],
                    ],
                    'title' => $title,
                ]);
        } else {
            abort(403);
        }
    }
}
