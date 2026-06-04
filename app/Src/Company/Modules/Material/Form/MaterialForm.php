<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Material\Form;

use App\Models\Material;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MaterialForm extends Form
{
    #[Validate]
    public $material_name;

    #[Validate]
    public $unit_id;

    #[Validate]
    public $available_stock;

    // #[Validate]
    public $materialId;

    // #[Validate]
    public $sku;

    // #[Validate]
    public $narration;

    public $id = 0;

    public function createMaterial(): void
    {
        $this->validate();
        Material::create($this->getValues());
    }

    public function rules(): array
    {
        return [
            'material_name' => ['required', Rule::unique(Material::class, 'material_name')
                ->where(fn ($query) => $query->where('company_id', Auth::user()->company_id))->ignore($this->id)],
            'unit_id' => ['required'],
            'available_stock' => ['required', 'numeric', 'min:0', 'digits_between:1,10'],
            // 'materialId' => ['required', Rule::unique(Material::class, 'materialId')->ignore($this->id)],
            // 'sku' => ['required', Rule::unique(Material::class, 'sku')->ignore($this->id)],
            // 'narration' => ['required'],
        ];
    }

    public function setMaterial(Material $material): void
    {
        $this->id = $material->id;

        $this->fill([
            'material_name' => $material->material_name,
            'unit_id' => $material->unit_id,
            'available_stock' => $material->available_stock,
            'narration' => $material->narration,
            // 'sku' => $material->sku,
            // 'materialId' => $material->materialId,
        ]);
    }

    public function update(Material $material): void
    {
        $this->validate();
        $material->update($this->getValues());
    }

    protected function getValues(): array
    {
        $unit = Unit::where('id', $this->unit_id)->first();
        $lastMaterial = Material::orderBy('id', 'desc')
            ->first();
        $nextId = $lastMaterial ? $lastMaterial->id + 1 : 1;
        $materialId = 'M'.$nextId;

        return [
            'material_name' => ucfirst($this->material_name ?? ''),
            'unit_id' => $this->unit_id,
            'unit_name' => $unit->name,
            'available_stock' => $this->available_stock,
            'narration' => ucfirst($this->narration ?? ''),
            'sku' => $materialId,
            'materialId' => $materialId,
            'company_id' => Auth::user()->company_id,
        ];
    }
}
