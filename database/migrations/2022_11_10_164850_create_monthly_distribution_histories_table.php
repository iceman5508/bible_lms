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
        Schema::create('monthly_distribution_histories', function (Blueprint $table) {
            $table->id();
            $table->string('month_year')->default(0);
            $table->integer('total_subscription')->default(0);
            $table->integer('total_enroll_course')->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('admin_commission', 12, 2)->default(0);
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('monthly_distribution_histories');
    }
};
