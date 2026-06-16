<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amcs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('customer_name');
            $table->date('from_date');
            $table->date('to_date');
            $table->unsignedTinyInteger('visit_count');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });

        Schema::create('amc_visit_months', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('amc_id');
            $table->date('visit_month');
            $table->unsignedTinyInteger('sort_order');
            $table->timestamps();

            $table->foreign('amc_id')->references('id')->on('amcs')->onDelete('cascade');
            $table->unique(['amc_id', 'sort_order'], 'amc_visit_sort_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amc_visit_months');
        Schema::dropIfExists('amcs');
    }
};
