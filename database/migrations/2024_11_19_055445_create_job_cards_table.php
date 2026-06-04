<?php

declare(strict_types=1);
use App\Utility\Enums\StatusEnum;
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
        Schema::create('job_cards', function (Blueprint $table) {
            $table->id();
            $table->string('job_no')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->date('job_date')->nullable();
            $table->string('description')->nullable();
            $table->string('product_name')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('qty')->nullable();
            $table->string('sq_ft')->nullable();
            $table->decimal('rate')->nullable();
            $table->decimal('amount')->nullable();
            $table->string('job_status')->nullable();
            $table->decimal('fitting_charge')->default(0)->nullable();
            $table->decimal('pesting_charge')->default(0)->nullable();
            $table->decimal('framing_charge')->default(0)->nullable();
            $table->decimal('transportation_charge')->default(0)->nullable();
            $table->tinyInteger('status')->default(StatusEnum::Active->value);
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_cards');
    }
};
