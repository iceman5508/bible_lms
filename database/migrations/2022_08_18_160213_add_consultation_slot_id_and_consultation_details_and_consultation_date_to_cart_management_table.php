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
        Schema::table('cart_management', function (Blueprint $table) {
            $table->unsignedBigInteger('consultation_slot_id')->after('product_id')->nullable();
            $table->text('consultation_details')->after('consultation_slot_id')->nullable();
            $table->string('consultation_date')->after('consultation_details')->nullable();
            $table->string('consultation_available_type')->after('consultation_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_management', function (Blueprint $table) {
            $table->dropColumn('consultation_slot_id');
            $table->dropColumn('consultation_details');
            $table->dropColumn('consultation_date');
        });
    }
};
