<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\TreatmentScheme\Form;

use App\Models\TreatmentScheme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TreatmentSchemeForm extends Form
{
    #[Validate]
    public $id = 0;

    #[Validate]
    public $name;

    public function createTreatmentScheme(): void
    {
        $this->validate();
        TreatmentScheme::create($this->getValues());
    }

    public function rules(): array
    {
        $this->resetErrorBag();

        return [
            'name' => [
                'required',
                'max:100',
                Rule::unique(TreatmentScheme::class, 'name')
                    ->where(fn ($query) => $query->where('company_id', Auth::user()->company_id))
                    ->ignore($this->id, 'id'),
            ],
        ];
    }

    public function setTreatmentScheme(TreatmentScheme $treatmentScheme): void
    {
        $this->id = $treatmentScheme->id;
        $this->fill([
            'name' => $treatmentScheme->name,
        ]);
    }

    public function update(TreatmentScheme $treatmentScheme): void
    {
        $this->validate();
        $treatmentScheme->update($this->getValues());
    }

    protected function getValues(): array
    {
        return [
            'company_id' => Auth::user()->company_id,
            'name' => ucfirst(trim((string) ($this->name ?? ''))),
        ];
    }
}
