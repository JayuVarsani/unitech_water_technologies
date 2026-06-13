<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('deliveries', 'entry_type')) {
            Schema::table('deliveries', function (Blueprint $table) {
                $table->string('entry_type')->default('form')->after('customer_name');
            });
        }

        Schema::table('deliveries', function (Blueprint $table) {
            $table->text('item_details')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('deliveries', 'entry_type')) {
            Schema::table('deliveries', function (Blueprint $table) {
                $table->dropColumn('entry_type');
            });
        }

        Schema::table('deliveries', function (Blueprint $table) {
            $table->text('item_details')->nullable(false)->change();
        });
    }
};
