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
        Schema::table('courses', function (Blueprint $table) {
            $table->tinyInteger('drip_content')->after('average_rating')->default(DRIP_SHOW_ALL)->comment('1=Show All, 2=sequence, 3=unlock after x day, 4=unlock by date, 5=unlock after finish pre-requisite');
            $table->integer('access_period')->after('drip_content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('drip_content');
            $table->dropColumn('access_period');
        });
    }
};
