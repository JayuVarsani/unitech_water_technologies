<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->date('visit_date');
            $table->string('visit_number')->nullable();
            $table->string('representative')->nullable();
            $table->string('site_name');
            $table->text('contact_address')->nullable();
            $table->string('contact_person');
            $table->decimal('plant_capacity_lph', 12, 2)->nullable();
            $table->string('amc_period')->nullable();
            $table->decimal('raw_water_pump_amps', 10, 2)->nullable();
            $table->string('raw_water_pump_make')->nullable();
            $table->string('mgf_backwash_done')->nullable();
            $table->string('acf_backwash_done')->nullable();
            $table->string('dosing_pump_working')->nullable();
            $table->string('antiscalent_make')->nullable();
            $table->decimal('antiscalent_dosage_ppm', 10, 2)->nullable();
            $table->string('mcf_replaced')->nullable();
            $table->date('mcf_last_replaced')->nullable();
            $table->string('mcf_size')->nullable();
            $table->string('hps_lps_working')->nullable();
            $table->decimal('hpp_amps', 10, 2)->nullable();
            $table->decimal('hpp_feed_pressure', 10, 2)->nullable();
            $table->decimal('hpp_reject_pressure', 10, 2)->nullable();
            $table->string('hpp_make_model')->nullable();
            $table->decimal('reading_raw_tds', 10, 2)->nullable();
            $table->decimal('reading_product_tds', 10, 2)->nullable();
            $table->decimal('reading_feed_flow', 10, 2)->nullable();
            $table->decimal('reading_product_flow', 10, 2)->nullable();
            $table->decimal('reading_feed_ph', 5, 2)->nullable();
            $table->decimal('reading_product_ph', 5, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->longText('client_signature')->nullable();
            $table->string('technician_signature_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
