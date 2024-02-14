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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('user_id');
            $table->string('order_number', 50);
            $table->decimal('sub_total')->default(0.00);
            $table->decimal('discount')->default(0.00);
            $table->decimal('shipping_cost')->default(0.00);
            $table->decimal('tax')->default(0.00);
            $table->decimal('platform_charge')->default(0.00);
            $table->decimal('grand_total')->default(0.00);
            $table->string('payment_method', 100)->nullable();
            $table->mediumText('customer_comment')->nullable();
            $table->string('payment_status', 15)->default('due')->comment('paid, due, free, pending, cancelled');
            $table->tinyInteger('delivery_status')->default(0)->comment('0=pending, 1=complete');
            $table->tinyInteger('created_by_type')->default(1)->comment('1=student, 2=admin')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
