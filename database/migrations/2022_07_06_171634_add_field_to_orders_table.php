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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('deposit_by')->after('payment_method')->nullable();
            $table->string('deposit_slip')->after('deposit_by')->nullable();
            $table->unsignedBigInteger('bank_id')->after('deposit_slip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('deposit_by');
            $table->dropColumn('deposit_slip');
        });
    }
};
