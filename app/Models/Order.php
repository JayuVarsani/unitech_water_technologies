<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Src\Company\Modules\Order\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;


#[ObservedBy(OrderObserver::class)]
class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'company_id',
        'customer_id',
        'date',
        'description',
        'status',
        'cancellation_reason',
        'total_amount',
        'fitting_charge',
        'pesting_charge',
        'transportation_charge',
        'discount',
        'estimated_amount',
        'rounded_amount',
        'created_at',
        'updated_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function orderJobs()
    {
        return $this->hasMany(OrderJob::class);
    }
}
