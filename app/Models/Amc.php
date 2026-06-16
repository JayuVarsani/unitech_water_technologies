<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Amc extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'visit_count' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function visitMonths(): HasMany
    {
        return $this->hasMany(AmcVisitMonth::class)->orderBy('sort_order');
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class)->orderBy('sort_order');
    }

    public function syncPendingVisits(): void
    {
        $this->load(['visitMonths', 'customer']);

        $sortOrders = $this->visitMonths->pluck('sort_order');

        $this->visits()
            ->where('status', Visit::STATUS_PENDING)
            ->whereNotIn('sort_order', $sortOrders)
            ->delete();

        foreach ($this->visitMonths as $visitMonth) {
            $visit = Visit::firstOrNew([
                'amc_id' => $this->id,
                'sort_order' => $visitMonth->sort_order,
            ]);

            if ($visit->exists && $visit->isCompleted()) {
                $visit->amc_visit_month_id = $visitMonth->id;
                $visit->save();

                continue;
            }

            $visit->fill([
                'company_id' => $this->company_id,
                'amc_visit_month_id' => $visitMonth->id,
                'visit_date' => $visitMonth->visit_month,
                'visit_number' => $visitMonth->sort_order.'/'.$this->visit_count,
                'site_name' => $this->customer_name,
                'contact_person' => $this->customer?->name ?? $this->customer_name,
                'contact_address' => $this->customer?->address,
                'status' => Visit::STATUS_PENDING,
            ]);

            $visit->save();
        }
    }
}
