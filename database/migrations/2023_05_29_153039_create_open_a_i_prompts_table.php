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
        Schema::create('open_a_i_prompts', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('is_image')->default(0);
            $table->string('category');
            $table->text('prompt');
            $table->tinyInteger('status')->default(STATUS_APPROVED);
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
        Schema::dropIfExists('open_a_i_prompts');
    }
};
