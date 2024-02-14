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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->bigInteger('user_id');
            $table->bigInteger('owner_user_id')->nullable();
            $table->bigInteger('course_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->integer('unit')->default(1);
            $table->decimal('unit_price')->default(0.00);
            $table->decimal('admin_commission')->default(0.00);
            $table->decimal('owner_balance')->default(0.00);
            $table->integer('sell_commission')->default(0)->comment('How much percentage get admin and calculate in admin_commission');
            $table->tinyInteger('type')->default(1)->comment('1=course, 2=product, 3=bundle course, 4=consultation');
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
        Schema::dropIfExists('order_items');
    }
};
