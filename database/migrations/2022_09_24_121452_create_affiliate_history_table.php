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
        Schema::create('affiliate_history', function (Blueprint $table) {
            $table->id();
            $table->string('hash')->unique();
            $table->bigInteger('user_id');
            $table->bigInteger('buyer_id');
            $table->bigInteger('order_id');
            $table->bigInteger('order_item_id');
            $table->bigInteger('course_id')->nullable();
            $table->bigInteger('bundle_id')->nullable();
            $table->bigInteger('consultation_slot_id')->nullable();
            $table->decimal('actual_price')->default(0.00);
            $table->decimal('discount')->default(0.00);
            $table->decimal('commission')->default(0.00);
            $table->decimal('commission_percentage')->default(0.00);
            $table->tinyInteger('status')->default(0)->comment('0=due,1=paid');
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
        Schema::dropIfExists('affiliate_history');
    }
};
