<?php

namespace App\Models;

use App\Src\Company\Modules\Inquiry\Observers\InquiryObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(InquiryObserver::class)]
class Inquiry extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'company_id',
        'customer_id',
        'date',
        'description',
        'total_amount',
        'discount',
        'fitting_charge',
        'pesting_charge',
        'transportation_charge',
        'status',
        'cancellation_reason',
        'estimated_amount',
        'rounded_amount',
        'created_by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function inquiryJobs()
    {
        return $this->hasMany(InquiryJob::class);
    }

    // public function orders()
    // {
    //     return $this->hasOne(Order::class);
    // }
    public function createdBy()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }
}
