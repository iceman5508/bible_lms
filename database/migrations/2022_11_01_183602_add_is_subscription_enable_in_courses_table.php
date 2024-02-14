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
            $table->tinyInteger('is_subscription_enable')->after('youtube_video_id')->default(PACKAGE_STATUS_ACTIVE);
        });
        
        Schema::table('bundles', function (Blueprint $table) {
            $table->tinyInteger('is_subscription_enable')->after('status')->default(PACKAGE_STATUS_ACTIVE);
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
            $table->dropColumn('is_subscription_enable');
        });
      
        Schema::table('bundles', function (Blueprint $table) {
            $table->dropColumn('is_subscription_enable');
        });
    }
};
