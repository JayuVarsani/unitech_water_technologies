<?php

declare(strict_types=1);

use App\Utility\Enums\StatusEnum;
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
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('address')->nullable();
            $table->unsignedBigInteger('staff_role_id')->nullable();
            $table->string('staff_role_name')->nullable();
            $table->date('joining_date')->nullable();
            $table->decimal('salary', 10)->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->tinyInteger('status')->default(StatusEnum::Active->value);
            $table->string('password');
            $table->timestamps();

            $table->foreign('staff_role_id')->references('id')->on('staff_roles')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
