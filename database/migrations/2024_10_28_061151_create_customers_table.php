<?php

declare(strict_types=1);

use App\Utility\Enums\AutoReminderTypeEnum;
use App\Utility\Enums\CustomerRegisterTypeEnum;
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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->unsignedBigInteger('customer_group_id')->nullable();
            $table->string('customer_group_name')->nullable();
            $table->enum('customer_register_type', array_column(CustomerRegisterTypeEnum::cases(), 'name'))->nullable();
            $table->string('gst_no')->nullable();
            $table->enum('auto_reminder', array_column(AutoReminderTypeEnum::cases(), 'name'))->nullable();
            $table->decimal('opening_balance', 10);
            $table->timestamps();

            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
