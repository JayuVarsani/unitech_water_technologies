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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->after('id')->nullable();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->after('id')->nullable();
        });

        Schema::table('staff_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_company_id_foreign');
            $table->dropColumn('company_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign('categories_company_id_foreign');
            $table->dropColumn('company_id');
        });

        Schema::table('staff_roles', function (Blueprint $table) {
            $table->dropForeign('staff_roles_company_id_foreign');
            $table->dropColumn('company_id');
        });
    }
};
