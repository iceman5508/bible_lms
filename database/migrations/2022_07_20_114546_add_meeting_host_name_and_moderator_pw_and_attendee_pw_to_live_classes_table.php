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
        Schema::table('live_classes', function (Blueprint $table) {
            $table->string('meeting_host_name')->after('meeting_password')->comment('zoom,bbb,jitsi')->nullable();
            $table->string('moderator_pw')->after('meeting_host_name')->comment('use only for bbb')->nullable();
            $table->string('attendee_pw')->after('moderator_pw')->comment('use only for bbb')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('live_classes', function (Blueprint $table) {
            $table->dropColumn('meeting_host_name');
            $table->dropColumn('moderator_pw');
            $table->dropColumn('attendee_pw');
        });
    }
};
