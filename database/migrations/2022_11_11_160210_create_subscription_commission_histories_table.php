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
        Schema::create('subscription_commission_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monthly_distribution_history_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('sub_amount', 12,2);
            $table->decimal('commission_percentage', 2,2);
            $table->decimal('admin_commission', 12,2);
            $table->decimal('total_amount');
            $table->dateTime('paid_at');
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
        Schema::dropIfExists('subscription_commission_histories');
    }
};
