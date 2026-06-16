<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Parameter\Form;

use App\Models\Parameter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ParameterForm extends Form
{
    #[Validate]
    public $id = 0;

    #[Validate]
    public $name;

    #[Validate]
    public $unit;

    public function createParameter(): void
    {
        $this->validate();
        Parameter::create($this->getValues());
    }

    public function rules(): array
    {
        $this->resetErrorBag();

        return [
            'name' => [
                'required',
                'max:100',
                Rule::unique(Parameter::class, 'name')
                    ->where(fn ($query) => $query->where('company_id', Auth::user()->company_id))
                    ->ignore($this->id, 'id'),
            ],
            'unit' => ['required', 'max:50'],
        ];
    }

    public function setParameter(Parameter $parameter): void
    {
        $this->id = $parameter->id;
        $this->fill([
            'name' => $parameter->name,
            'unit' => $parameter->unit,
        ]);
    }

    public function update(Parameter $parameter): void
    {
        $this->validate();
        $parameter->update($this->getValues());
    }

    protected function getValues(): array
    {
        return [
            'company_id' => Auth::user()->company_id,
            'name' => ucfirst(trim((string) ($this->name ?? ''))),
            'unit' => trim((string) ($this->unit ?? '')),
        ];
    }
}
