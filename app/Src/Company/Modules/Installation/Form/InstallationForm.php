<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Installation\Form;

use App\Models\Installation;
use App\Models\Parameter;
use App\Models\TreatmentScheme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Form;

class InstallationForm extends Form
{
    public $installationDate = '';

    public $engineerName = '';

    public $clientSignature = '';

    public array $parameterValues = [];

    public array $schemeDetails = [];

    public $id = 0;

    public function createInstallation(): Installation
    {
        $this->validate();

        return DB::transaction(function () {
            $installation = Installation::create($this->getValues());
            $this->syncRelations($installation);

            return $installation;
        });
    }

    public function rules(): array
    {
        return [
            'installationDate' => ['required', 'date'],
            'engineerName' => ['required', 'string', 'max:100'],
            'clientSignature' => ['required', 'string'],
            'parameterValues.*' => ['nullable', 'numeric'],
            'schemeDetails.*.make' => ['nullable', 'string', 'max:100'],
            'schemeDetails.*.model' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'installationDate' => 'installation date',
            'engineerName' => 'engineer name',
            'clientSignature' => 'signature',
        ];
    }

    public function setInstallation(Installation $installation): void
    {
        $this->id = $installation->id;

        $this->fill([
            'installationDate' => $installation->installation_date?->format('Y-m-d') ?? '',
            'engineerName' => $installation->engineer_name ?? '',
            'clientSignature' => $installation->client_signature ?? '',
        ]);

        $this->parameterValues = [];
        foreach ($installation->installationParameters as $item) {
            $this->parameterValues[$item->parameter_id] = $item->value;
        }

        $this->schemeDetails = [];
        foreach ($installation->installationTreatmentSchemes as $item) {
            $this->schemeDetails[$item->treatment_scheme_id] = [
                'make' => $item->make ?? '',
                'model' => $item->model ?? '',
            ];
        }
    }

    public function update(Installation $installation): void
    {
        $this->validate();

        DB::transaction(function () use ($installation) {
            $installation->update($this->getValues());
            $this->syncRelations($installation);
        });
    }

    protected function getValues(): array
    {
        return [
            'company_id' => Auth::user()->company_id,
            'installation_date' => $this->installationDate,
            'engineer_name' => trim((string) $this->engineerName),
            'client_signature' => $this->clientSignature,
        ];
    }

    protected function syncRelations(Installation $installation): void
    {
        $companyId = Auth::user()->company_id;
        $validParameterIds = Parameter::where('company_id', $companyId)->pluck('id');
        $validSchemeIds = TreatmentScheme::where('company_id', $companyId)->pluck('id');

        $installation->installationParameters()->delete();
        foreach ($this->parameterValues as $parameterId => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            if (! $validParameterIds->contains((int) $parameterId)) {
                continue;
            }

            $installation->installationParameters()->create([
                'parameter_id' => (int) $parameterId,
                'value' => $value,
            ]);
        }

        $installation->installationTreatmentSchemes()->delete();
        foreach ($this->schemeDetails as $schemeId => $details) {
            $make = trim((string) ($details['make'] ?? ''));
            $model = trim((string) ($details['model'] ?? ''));

            if ($make === '' && $model === '') {
                continue;
            }

            if (! $validSchemeIds->contains((int) $schemeId)) {
                continue;
            }

            $installation->installationTreatmentSchemes()->create([
                'treatment_scheme_id' => (int) $schemeId,
                'make' => $make ?: null,
                'model' => $model ?: null,
            ]);
        }
    }
}
