<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Category\Form;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CategoryForm extends Form
{
    #[Validate]
    public $id = 0;

    #[Validate]
    public $name;

    public function createCategory(): void
    {
        $this->validate();
        Category::create($this->getValues());
    }

    public function rules(): array
    {
        return ['name' => ['required', 'max:30', Rule::unique(Category::class, 'name')->where(fn ($query) => $query->where('company_id', Auth::user()->company_id))->ignore($this->id, 'id')]];
    }

    public function setCategory(Category $category): void
    {
        $this->id = $category->id;
        $this->fill(['name' => $category->name]);
    }

    public function update(Category $category): void
    {
        $this->validate();
        $category->update($this->getValues());
    }

    protected function getValues(): array
    {
        return ['company_id' => Auth::user()->company_id, 'name' => ucfirst($this->name ?? '')];
    }
}
