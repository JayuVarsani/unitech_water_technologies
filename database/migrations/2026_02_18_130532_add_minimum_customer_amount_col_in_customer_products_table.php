<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customer_products', function (Blueprint $table) {
            $table->decimal('customer_min_amount', 10)->after('product_price_new')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_products', function (Blueprint $table) {
            $table->dropColumn('customer_min_amount');
        });
    }
};
