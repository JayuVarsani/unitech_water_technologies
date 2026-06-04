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
        Schema::table('order_jobs', function (Blueprint $table) {
            $table->foreignId('design_by')
                ->nullable()
                ->after('staff_id')
                ->constrained('staffs')
                ->nullOnDelete();
            $table->dropForeign(['staff_id']);
            $table->dropColumn('staff_id');
            $table->foreignId('print_by')
                ->nullable()
                ->after('product_name')
                ->constrained('staffs')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_jobs', function (Blueprint $table) {
            $table->dropForeign(['design_by']);
            $table->dropColumn('design_by');
            $table->dropForeign(['print_by']);
            $table->dropColumn('print_by');
            $table->foreignId('staff_id')
                ->nullable()
                ->after('product_name')
                ->constrained('staffs')
                ->nullOnDelete();
        });
    }
};
