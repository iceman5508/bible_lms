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
        Schema::create('about_us_generals', function (Blueprint $table) {
            $table->id();
            $table->string('gallery_area_title')->nullable();
            $table->text('gallery_area_subtitle')->nullable();
            $table->string('our_history_title')->nullable();
            $table->text('our_history_subtitle')->nullable();
            $table->string('upgrade_skill_logo')->nullable();
            $table->string('upgrade_skill_title')->nullable();
            $table->text('upgrade_skill_subtitle')->nullable();
            $table->string('upgrade_skill_button_name')->nullable();
            $table->string('team_member_logo')->nullable();
            $table->string('team_member_title')->nullable();
            $table->text('team_member_subtitle')->nullable();
            $table->string('instructor_support_title')->nullable();
            $table->text('instructor_support_subtitle')->nullable();
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
        Schema::dropIfExists('about_us_generals');
    }
};
