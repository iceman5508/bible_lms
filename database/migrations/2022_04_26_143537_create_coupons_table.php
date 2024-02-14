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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('coupon_code_name');
            $table->tinyInteger('coupon_type')->comment('1=Global,2=Instructor, 3=Course');
            $table->tinyInteger('status')->comment('1=activate, 0=deactivated')->default(1);
            $table->unsignedBigInteger('creator_id')->comment('creator_id=user_id')->nullable();
            $table->decimal('percentage')->default(0.00)->nullable();
            $table->integer('minimum_amount')->nullable();
            $table->integer('maximum_use_limit')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('coupons');
    }
};
