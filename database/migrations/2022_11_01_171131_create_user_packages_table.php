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
        Schema::create('user_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('payment_id');
            $table->enum('subscription_type', ['MONTHLY', 'YEARLY']);
            $table->dateTime('enroll_date');
            $table->dateTime('expired_date');
            $table->integer('student')->default(0);
            $table->integer('instructor')->default(0);
            $table->integer('course')->default(0);
            $table->integer('consultancy')->default(0);
            $table->integer('subscription_course')->default(0);
            $table->integer('bundle_course')->default(0);
            $table->integer('product')->default(0);
            $table->integer('device')->default(0);
            $table->integer('admin_commission')->default(0);
            $table->tinyInteger('status')->default(PACKAGE_STATUS_ACTIVE);
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
        Schema::dropIfExists('user_packages');
    }
};
