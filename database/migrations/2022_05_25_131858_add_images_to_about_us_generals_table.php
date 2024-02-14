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
        Schema::table('about_us_generals', function (Blueprint $table) {
            $table->string('gallery_first_image')->after('gallery_area_subtitle')->nullable();
            $table->string('gallery_second_image')->after('gallery_area_subtitle')->nullable();
            $table->string('gallery_third_image')->after('gallery_area_subtitle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('about_us_generals', function (Blueprint $table) {
            $table->dropColumn('gallery_first_image');
            $table->dropColumn('gallery_second_image');
            $table->dropColumn('gallery_third_image');
        });
    }
};
