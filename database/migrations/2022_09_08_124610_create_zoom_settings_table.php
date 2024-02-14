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
        Schema::create('zoom_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('api_key');
            $table->string('api_secret');
            $table->string('timezone');
            $table->string('host_video')->default(false)->comment('true, false');
            $table->string('participant_video')->default(false)->comment('true, false');
            $table->string('waiting_room')->default(false)->comment('true, false');
            $table->tinyInteger('status')->default(0)->comment('0=disable, 1=active');
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
        Schema::dropIfExists('zoom_settings');
    }
};
