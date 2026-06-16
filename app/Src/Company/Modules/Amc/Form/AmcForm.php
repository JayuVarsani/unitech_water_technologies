<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Amc\Form;

use App\Models\Amc;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Form;

class AmcForm extends Form
{
    public $fromDate = '';

    public $toDate = '';

    public $customerId = '';

    public $visitCount = '';

    public array $visitMonths = [];

    public $id = 0;

    public function createAmc(): Amc
    {
        $this->validateDuplicateMonths();
        $this->validate();

        return DB::transaction(function () {
            $amc = Amc::create($this->getValues());
            $this->syncVisitMonths($amc);
            $amc->syncPendingVisits();

            return $amc;
        });
    }

    public function rules(): array
    {
        $rules = [
            'fromDate' => ['required', 'date'],
            'toDate' => ['required', 'date', 'after_or_equal:fromDate'],
            'customerId' => [
                'required',
                Rule::exists('customers', 'id')->where(
                    fn ($query) => $query->where('company_id', Auth::user()->company_id)
                ),
            ],
            'visitCount' => ['required', 'integer', 'min:1', 'max:24'],
        ];

        $count = (int) $this->visitCount;
        for ($i = 0; $i < $count; $i++) {
            $rules["visitMonths.{$i}"] = ['required', 'date_format:Y-m'];
        }

        return $rules;
    }

    public function validationAttributes(): array
    {
        $attributes = [
            'fromDate' => 'from date',
            'toDate' => 'to date',
            'customerId' => 'company',
            'visitCount' => 'visit count',
        ];

        $count = (int) $this->visitCount;
        for ($i = 0; $i < $count; $i++) {
            $attributes["visitMonths.{$i}"] = 'visit month '.($i + 1);
        }

        return $attributes;
    }

    public function setAmc(Amc $amc): void
    {
        $this->id = $amc->id;

        $this->fill([
            'fromDate' => $amc->from_date?->format('Y-m-d') ?? '',
            'toDate' => $amc->to_date?->format('Y-m-d') ?? '',
            'customerId' => $amc->customer_id ?? '',
            'visitCount' => $amc->visit_count ?? '',
        ]);

        $this->visitMonths = $amc->visitMonths
            ->sortBy('sort_order')
            ->map(fn ($item) => $item->visit_month?->format('Y-m') ?? '')
            ->values()
            ->all();
    }

    public function update(Amc $amc): void
    {
        $this->validateDuplicateMonths();
        $this->validate();

        DB::transaction(function () use ($amc) {
            $amc->update($this->getValues());
            $this->syncVisitMonths($amc);
            $amc->syncPendingVisits();
        });
    }

    public function updatedVisitCount($value): void
    {
        $this->syncVisitMonthFields((int) $value);
    }

    public static function defaultFromDate(): string
    {
        return now()->format('Y-m-d');
    }

    public static function defaultToDate(): string
    {
        return now()->addYear()->subDay()->format('Y-m-d');
    }

    protected function getValues(): array
    {
        $customer = Customer::where('id', $this->customerId)
            ->where('company_id', Auth::user()->company_id)
            ->firstOrFail();

        return [
            'company_id' => Auth::user()->company_id,
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'from_date' => $this->fromDate,
            'to_date' => $this->toDate,
            'visit_count' => (int) $this->visitCount,
        ];
    }

    protected function syncVisitMonths(Amc $amc): void
    {
        $amc->visitMonths()->delete();

        foreach (array_values($this->visitMonths) as $index => $month) {
            if ($month === null || $month === '') {
                continue;
            }

            $amc->visitMonths()->create([
                'visit_month' => Carbon::createFromFormat('Y-m', $month)->startOfMonth()->format('Y-m-d'),
                'sort_order' => $index + 1,
            ]);
        }
    }

    protected function syncVisitMonthFields(int $count): void
    {
        $count = max(0, min(24, $count));
        $current = count($this->visitMonths);

        if ($count > $current) {
            for ($i = $current; $i < $count; $i++) {
                $this->visitMonths[$i] = '';
            }
        } elseif ($count < $current) {
            $this->visitMonths = array_values(array_slice($this->visitMonths, 0, $count));
        }
    }

    protected function validateDuplicateMonths(): void
    {
        $months = array_values(array_filter($this->visitMonths, fn ($month) => $month !== '' && $month !== null));

        if (count($months) !== count(array_unique($months))) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'visitCount' => 'Each visit month must be unique.',
            ]);
        }
    }
}
