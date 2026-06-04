<?php

declare(strict_types=1);

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
        Schema::create('job_wastages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->string('damage_type')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('material_id')->default(0);
            $table->string('width')->nullable()->default(0);
            $table->string('height')->nullable()->default(0);
            $table->timestamps();
            $table->foreign('job_id')->references('id')->on('job_cards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_wastages');
    }
};
