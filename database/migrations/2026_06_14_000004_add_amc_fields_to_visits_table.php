<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->unsignedBigInteger('amc_id')->nullable()->after('company_id');
            $table->unsignedBigInteger('amc_visit_month_id')->nullable()->after('amc_id');
            $table->unsignedBigInteger('staff_id')->nullable()->after('representative');
            $table->unsignedTinyInteger('sort_order')->nullable()->after('visit_number');
            $table->string('status')->default('pending')->after('sort_order');

            $table->foreign('amc_id')->references('id')->on('amcs')->nullOnDelete();
            $table->foreign('amc_visit_month_id')->references('id')->on('amc_visit_months')->nullOnDelete();
            $table->foreign('staff_id')->references('id')->on('staffs')->nullOnDelete();
            $table->unique(['amc_id', 'sort_order'], 'visit_amc_sort_unique');
        });

        DB::table('visits')->whereNull('amc_id')->update(['status' => 'completed']);
    }

    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropForeign(['amc_id']);
            $table->dropForeign(['amc_visit_month_id']);
            $table->dropForeign(['staff_id']);
            $table->dropUnique('visit_amc_sort_unique');
            $table->dropColumn(['amc_id', 'amc_visit_month_id', 'staff_id', 'sort_order', 'status']);
        });
    }
};
