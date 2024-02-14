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
        Schema::table('assignment_submits', function (Blueprint $table) {
            $table->string('original_filename')->after('file')->nullable();
            $table->string('size')->after('original_filename')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assignment_submits', function (Blueprint $table) {
            $table->dropColumn('original_filename');
            $table->dropColumn('size');
        });
    }
};
