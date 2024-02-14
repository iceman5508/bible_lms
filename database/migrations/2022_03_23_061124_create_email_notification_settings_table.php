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
        Schema::create('email_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('user_id');
            $table->string('updates_from_classes', 10)->default('no')->comment('yes, no');
            $table->string('updates_from_teacher_discussion', 10)->default('no')->comment('yes, no');
            $table->string('activity_on_your_project', 10)->default('no')->comment('yes, no');
            $table->string('activity_on_your_discussion_comment', 10)->default('no')->comment('yes, no');
            $table->string('reply_comment', 10)->default('no')->comment('yes, no');
            $table->string('new_follower', 10)->default('no')->comment('yes, no');
            $table->string('new_class_by_someone_you_follow', 10)->default('no')->comment('yes, no');
            $table->string('new_live_session', 10)->default('no')->comment('yes, no');
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
        Schema::dropIfExists('email_notification_settings');
    }
};
