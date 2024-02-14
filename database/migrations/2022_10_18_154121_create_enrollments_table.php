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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->bigInteger('user_id');
            $table->bigInteger('owner_user_id')->nullable();
            $table->bigInteger('course_id')->nullable();
            $table->bigInteger('consultation_slot_id')->nullable();
            $table->bigInteger('bundle_id')->nullable();
            $table->double('completed_time')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->tinyInteger('status')->default(ACCESS_PERIOD_ACTIVE);
            $table->timestamps();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('completed_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollments');

        Schema::table('order_items', function (Blueprint $table) {
            $table->double('completed_time')->nullable()->after('course_id');
        });
    }
};
