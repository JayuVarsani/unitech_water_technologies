<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('installation_treatment_schemes');
        Schema::dropIfExists('installation_parameters');
        Schema::dropIfExists('installations');

        Schema::create('installations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->date('installation_date');
            $table->longText('client_signature')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::create('installation_parameters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('installation_id');
            $table->unsignedBigInteger('parameter_id');
            $table->decimal('value', 12, 4);
            $table->timestamps();

            $table->foreign('installation_id')->references('id')->on('installations')->onDelete('cascade');
            $table->foreign('parameter_id')->references('id')->on('parameters')->onDelete('cascade');
            $table->unique(['installation_id', 'parameter_id'], 'inst_param_unique');
        });

        Schema::create('installation_treatment_schemes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('installation_id');
            $table->unsignedBigInteger('treatment_scheme_id');
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->timestamps();

            $table->foreign('installation_id')->references('id')->on('installations')->onDelete('cascade');
            $table->foreign('treatment_scheme_id')->references('id')->on('treatment_schemes')->onDelete('cascade');
            $table->unique(['installation_id', 'treatment_scheme_id'], 'inst_scheme_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installation_treatment_schemes');
        Schema::dropIfExists('installation_parameters');
        Schema::dropIfExists('installations');
    }
};
