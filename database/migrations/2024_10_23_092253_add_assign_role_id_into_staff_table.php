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
        Schema::table('staffs', function (Blueprint $table) {
            $table->unsignedBigInteger('assign_role_id')->nullable()->after('staff_role_name');
            $table->string('assign_role_name')->nullable()->after('assign_role_id');

            // $table->foreign('assign_role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staffs', function (Blueprint $table) {
            // $table->dropForeign('staffs_assign_role_id_foreign');
            // $table->dropColumn('assign_role_id');
            $table->dropColumn('assign_role_name');
        });
    }
};
