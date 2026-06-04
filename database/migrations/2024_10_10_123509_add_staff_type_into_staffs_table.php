<?php

declare(strict_types=1);

use App\Utility\Enums\CompanyTypeEnum;
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
            $types = collect(CompanyTypeEnum::cases())->pluck('value')->toArray();
            $table->unsignedBigInteger('company_id')->after('id')->nullable();
            $table->enum('type', $types)->default(CompanyTypeEnum::Admin->value)->after('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staffs', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropForeign('staffs_company_id_foreign');
            $table->dropColumn('company_id');

        });
    }
};
