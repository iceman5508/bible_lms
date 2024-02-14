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
        Schema::table('homes', function (Blueprint $table) {
            $table->tinyInteger('special_feature_area')->after('banner_image')->default(1)->comment('1=active, 2=disable');
            $table->tinyInteger('courses_area')->after('special_feature_area')->default(1)->comment('1=active, 2=disable');
            $table->tinyInteger('bundle_area')->after('courses_area')->default(1)->comment('1=active, 2=disable');
            $table->tinyInteger('top_category_area')->after('bundle_area')->default(1)->comment('1=active, 2=disable');
            $table->tinyInteger('consultation_area')->after('top_category_area')->default(1)->comment('1=active, 2=disable');
            $table->tinyInteger('instructor_area')->after('consultation_area')->default(1)->comment('1=active, 2=disable');
            $table->tinyInteger('video_area')->after('instructor_area')->default(1)->comment('1=active, 2=disable');
            $table->tinyInteger('customer_says_area')->after('video_area')->default(1)->comment('1=active, 2=disable');
            $table->tinyInteger('achievement_area')->after('customer_says_area')->default(1)->comment('1=active, 2=disable');
            $table->tinyInteger('faq_area')->after('achievement_area')->default(1)->comment('1=active, 2=disable');
            $table->tinyInteger('instructor_support_area')->after('faq_area')->default(1)->comment('1=active, 2=disable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homes', function (Blueprint $table) {
            $table->dropColumn('special_feature_area');
            $table->dropColumn('courses_area');
            $table->dropColumn('bundle_area');
            $table->dropColumn('top_category_area');
            $table->dropColumn('consultation_area');
            $table->dropColumn('instructor_area');
            $table->dropColumn('video_area');
            $table->dropColumn('customer_says_area');
            $table->dropColumn('achievement_area');
            $table->dropColumn('faq_area');
            $table->dropColumn('instructor_support_area');
        });
    }
};
