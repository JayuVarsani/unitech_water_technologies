<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'product_materials', 'product_id', 'material_id');
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_products', 'productId', 'customer_id');
    }

    public function inquiryJobs()
    {
        return $this->hasMany(InquiryJob::class);
    }

    public function orderJobs()
    {
        return $this->hasMany(OrderJob::class);
    }
}
