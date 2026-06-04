<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerProduct extends Model
{
    use HasFactory;

    protected $fillable = ['productId', 'company_id', 'customer_id', 'product_price', 'product_price_new', 'customer_min_amount'];

    protected $casts = [
        'product_price' => 'decimal:2',
        'product_price_new' => 'decimal:2',
        'customer_min_amount' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
