<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('payment_id');
            $table->bigInteger('user_id');
            $table->string('order_number', 50);
            $table->decimal('sub_total')->default(0.00);
            $table->decimal('tax')->default(0.00);
            $table->string('payment_currency')->nullable();
            $table->decimal('platform_charge')->default(0.00);
            $table->decimal('conversion_rate', 28,8)->default(0.00);
            $table->decimal('grand_total_with_conversation_rate', 28,8)->default(0.00);
            $table->string('deposit_by')->nullable();
            $table->string('deposit_slip')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->decimal('grand_total')->default(0.00);
            $table->string('payment_method', 100)->nullable();
            $table->longText('payment_details')->nullable();
            $table->string('payment_status', 15)->default('due')->comment('paid, due, free, pending, cancelled');
            $table->tinyInteger('created_by_type')->default(1)->comment('1=student, 2=instructor')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
