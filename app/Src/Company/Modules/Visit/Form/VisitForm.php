<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Visit\Form;

use App\Models\Staff;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Form;

class VisitForm extends Form
{
    public $visitDate = '';

    public $visitNumber = '';

    public $representative = '';

    public $staffId = '';

    public $siteName = '';

    public $contactAddress = '';

    public $contactPerson = '';

    public $plantCapacityLph = '';

    public $amcPeriod = '';

    public $rawWaterPumpAmps = '';

    public $rawWaterPumpMake = '';

    public $mgfBackwashDone = '';

    public $acfBackwashDone = '';

    public $dosingPumpWorking = '';

    public $antiscalentMake = '';

    public $antiscalentDosagePpm = '';

    public $mcfReplaced = '';

    public $mcfLastReplaced = '';

    public $mcfSize = '';

    public $hpsLpsWorking = '';

    public $hppAmps = '';

    public $hppFeedPressure = '';

    public $hppRejectPressure = '';

    public $hppMakeModel = '';

    public $readingRawTds = '';

    public $readingProductTds = '';

    public $readingFeedFlow = '';

    public $readingProductFlow = '';

    public $readingFeedPh = '';

    public $readingProductPh = '';

    public $remarks = '';

    public $clientSignature = '';

    public $technicianSignatureName = '';

    public $id = 0;

    public function completeVisit(Visit $visit): void
    {
        $this->validate($this->completeRules());
        $visit->update(array_merge($this->getValues(), [
            'status' => Visit::STATUS_COMPLETED,
        ]));
    }

    public function completeRules(): array
    {
        return array_merge($this->rules(), [
            'staffId' => [
                'required',
                Rule::exists('staffs', 'id')->where(
                    fn ($query) => $query->where('company_id', Auth::user()->company_id)
                ),
            ],
        ]);
    }

    public function createVisit(): Visit
    {
        $this->validate();

        return Visit::create($this->getValues());
    }

    public function rules(): array
    {
        $yesNo = ['nullable', 'in:yes,no'];
        $numeric = ['nullable', 'numeric', 'min:0'];

        return [
            'visitDate' => ['required', 'date'],
            'visitNumber' => ['nullable', 'string', 'max:50'],
            'representative' => ['nullable', 'string', 'max:255'],
            'siteName' => ['required', 'string', 'max:255'],
            'contactAddress' => ['nullable', 'string', 'max:1000'],
            'contactPerson' => ['required', 'string', 'max:255'],
            'plantCapacityLph' => $numeric,
            'amcPeriod' => ['nullable', 'string', 'max:255'],
            'rawWaterPumpAmps' => $numeric,
            'rawWaterPumpMake' => ['nullable', 'string', 'max:255'],
            'mgfBackwashDone' => $yesNo,
            'acfBackwashDone' => $yesNo,
            'dosingPumpWorking' => $yesNo,
            'antiscalentMake' => ['nullable', 'string', 'max:255'],
            'antiscalentDosagePpm' => $numeric,
            'mcfReplaced' => $yesNo,
            'mcfLastReplaced' => ['nullable', 'date'],
            'mcfSize' => ['nullable', 'string', 'max:100'],
            'hpsLpsWorking' => $yesNo,
            'hppAmps' => $numeric,
            'hppFeedPressure' => $numeric,
            'hppRejectPressure' => $numeric,
            'hppMakeModel' => ['nullable', 'string', 'max:255'],
            'readingRawTds' => $numeric,
            'readingProductTds' => $numeric,
            'readingFeedFlow' => $numeric,
            'readingProductFlow' => $numeric,
            'readingFeedPh' => ['nullable', 'numeric', 'min:0', 'max:14'],
            'readingProductPh' => ['nullable', 'numeric', 'min:0', 'max:14'],
            'remarks' => ['nullable', 'string', 'max:5000'],
            'clientSignature' => ['required', 'string'],
            'technicianSignatureName' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'visitDate' => 'date of visit',
            'visitNumber' => 'visit number',
            'siteName' => 'site name',
            'contactPerson' => 'contact person',
            'clientSignature' => 'client signature',
            'staffId' => 'representative',
        ];
    }

    public function setVisit(Visit $visit): void
    {
        $this->id = $visit->id;

        $this->fill([
            'visitDate' => $visit->visit_date?->format('Y-m-d') ?? '',
            'visitNumber' => $visit->visit_number ?? '',
            'representative' => $visit->representative ?? '',
            'staffId' => $visit->staff_id ?? '',
            'siteName' => $visit->site_name ?? '',
            'contactAddress' => $visit->contact_address ?? '',
            'contactPerson' => $visit->contact_person ?? '',
            'plantCapacityLph' => $visit->plant_capacity_lph ?? '',
            'amcPeriod' => $visit->amc_period ?? '',
            'rawWaterPumpAmps' => $visit->raw_water_pump_amps ?? '',
            'rawWaterPumpMake' => $visit->raw_water_pump_make ?? '',
            'mgfBackwashDone' => $visit->mgf_backwash_done ?? '',
            'acfBackwashDone' => $visit->acf_backwash_done ?? '',
            'dosingPumpWorking' => $visit->dosing_pump_working ?? '',
            'antiscalentMake' => $visit->antiscalent_make ?? '',
            'antiscalentDosagePpm' => $visit->antiscalent_dosage_ppm ?? '',
            'mcfReplaced' => $visit->mcf_replaced ?? '',
            'mcfLastReplaced' => $visit->mcf_last_replaced?->format('Y-m-d') ?? '',
            'mcfSize' => $visit->mcf_size ?? '',
            'hpsLpsWorking' => $visit->hps_lps_working ?? '',
            'hppAmps' => $visit->hpp_amps ?? '',
            'hppFeedPressure' => $visit->hpp_feed_pressure ?? '',
            'hppRejectPressure' => $visit->hpp_reject_pressure ?? '',
            'hppMakeModel' => $visit->hpp_make_model ?? '',
            'readingRawTds' => $visit->reading_raw_tds ?? '',
            'readingProductTds' => $visit->reading_product_tds ?? '',
            'readingFeedFlow' => $visit->reading_feed_flow ?? '',
            'readingProductFlow' => $visit->reading_product_flow ?? '',
            'readingFeedPh' => $visit->reading_feed_ph ?? '',
            'readingProductPh' => $visit->reading_product_ph ?? '',
            'remarks' => $visit->remarks ?? '',
            'clientSignature' => $visit->client_signature ?? '',
            'technicianSignatureName' => $visit->technician_signature_name ?? '',
        ]);
    }

    public function update(Visit $visit): void
    {
        $this->validate();
        $visit->update($this->getValues());
    }

    protected function getValues(): array
    {
        $representative = $this->representative ?: null;
        $staffId = $this->staffId ?: null;

        if ($staffId) {
            $staff = Staff::where('id', $staffId)
                ->where('company_id', Auth::user()->company_id)
                ->first();

            if ($staff) {
                $representative = $staff->name;
                $staffId = $staff->id;
            }
        }

        return [
            'company_id' => Auth::user()->company_id,
            'visit_date' => $this->visitDate,
            'visit_number' => $this->visitNumber ?: null,
            'representative' => $representative,
            'staff_id' => $staffId,
            'site_name' => $this->siteName,
            'contact_address' => $this->contactAddress ?: null,
            'contact_person' => $this->contactPerson,
            'plant_capacity_lph' => $this->nullableDecimal($this->plantCapacityLph),
            'amc_period' => $this->amcPeriod ?: null,
            'raw_water_pump_amps' => $this->nullableDecimal($this->rawWaterPumpAmps),
            'raw_water_pump_make' => $this->rawWaterPumpMake ?: null,
            'mgf_backwash_done' => $this->mgfBackwashDone ?: null,
            'acf_backwash_done' => $this->acfBackwashDone ?: null,
            'dosing_pump_working' => $this->dosingPumpWorking ?: null,
            'antiscalent_make' => $this->antiscalentMake ?: null,
            'antiscalent_dosage_ppm' => $this->nullableDecimal($this->antiscalentDosagePpm),
            'mcf_replaced' => $this->mcfReplaced ?: null,
            'mcf_last_replaced' => $this->mcfLastReplaced ?: null,
            'mcf_size' => $this->mcfSize ?: null,
            'hps_lps_working' => $this->hpsLpsWorking ?: null,
            'hpp_amps' => $this->nullableDecimal($this->hppAmps),
            'hpp_feed_pressure' => $this->nullableDecimal($this->hppFeedPressure),
            'hpp_reject_pressure' => $this->nullableDecimal($this->hppRejectPressure),
            'hpp_make_model' => $this->hppMakeModel ?: null,
            'reading_raw_tds' => $this->nullableDecimal($this->readingRawTds),
            'reading_product_tds' => $this->nullableDecimal($this->readingProductTds),
            'reading_feed_flow' => $this->nullableDecimal($this->readingFeedFlow),
            'reading_product_flow' => $this->nullableDecimal($this->readingProductFlow),
            'reading_feed_ph' => $this->nullableDecimal($this->readingFeedPh),
            'reading_product_ph' => $this->nullableDecimal($this->readingProductPh),
            'remarks' => $this->remarks ?: null,
            'client_signature' => $this->clientSignature,
            'technician_signature_name' => $this->technicianSignatureName ?: null,
        ];
    }

    private function nullableDecimal(mixed $value): ?float
    {
        if ($value === '' || $value === null) {
            return null;
        }

        return (float) $value;
    }
}
