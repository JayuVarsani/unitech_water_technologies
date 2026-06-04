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
            $table->decimal('pesting_charge', 10)->after('amount')->nullable();
            $table->decimal('fitting_charge', 10)->after('pesting_charge')->nullable();
            $table->decimal('transportation_charge', 10)->after('fitting_charge')->nullable();
            $table->text('narration')->after('transportation_charge')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_jobs', function (Blueprint $table) {
            $table->dropColumn('pesting_charge');
            $table->dropColumn('fitting_charge');
            $table->dropColumn('transportation_charge');
            $table->dropColumn('narration');
        });
    }
};
