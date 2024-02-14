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
            $table->unsignedBigInteger('bundle_id')->after('product_id')->nullable();
            $table->text('bundle_course_ids')->after('bundle_id')->nullable();
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
            $table->dropColumn('bundle_id');
            $table->dropColumn('bundle_course_ids');
        });
    }
};
