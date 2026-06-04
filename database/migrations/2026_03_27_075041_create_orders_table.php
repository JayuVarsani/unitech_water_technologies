<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Utility\Enums\OrderStatusEnum;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');

            $table->date('date');
            $table->text('description')->nullable();
            $table->string('status')->default(OrderStatusEnum::Confirmed->value);
            $table->decimal('fitting_charge')->nullable();
            $table->decimal('pesting_charge')->nullable();
            $table->decimal('transportation_charge')->nullable();
            $table->decimal('discount')->nullable();
            $table->decimal('total_amount')->default(0);
            $table->decimal('estimated_amount')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
