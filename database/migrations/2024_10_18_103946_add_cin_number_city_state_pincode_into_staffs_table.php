<?php

declare(strict_types=1);

use App\Utility\Enums\CompanyRegistorTypeEnum;
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
        Schema::table('staffs', function (Blueprint $table) {
            $table->after('contact_number', function ($table) {
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('pincode')->nullable();
            });
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->after('gst_no', function ($table) {
                $table->string('cin_number')->nullable();
                $table->enum('company_registor_type', array_column(CompanyRegistorTypeEnum::cases(), 'name'))->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staffs', function (Blueprint $table) {
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('pincode');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('cin_number');
            $table->dropColumn('company_registor_type');
        });
    }
};
