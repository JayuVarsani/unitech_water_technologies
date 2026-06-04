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
        Schema::table('inquiry_jobs', function (Blueprint $table) {
            $table->dropColumn('transportation_charge');
        });
        Schema::table('order_jobs', function (Blueprint $table) {
            $table->dropColumn('transportation_charge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiry_jobs', function (Blueprint $table) {
            $table->decimal('transportation_charge', 10)->after('fitting_charge')->nullable();
        });
        Schema::table('order_jobs', function (Blueprint $table) {
            $table->decimal('transportation_charge', 10)->after('fitting_charge')->nullable();
        });
    }
};
