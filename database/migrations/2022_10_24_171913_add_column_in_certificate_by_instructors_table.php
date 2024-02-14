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
        Schema::table('certificate_by_instructors', function (Blueprint $table) {
            $table->integer('role_2_x_position')->after('signature')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificate_by_instructors', function (Blueprint $table) {
            $table->dropColumn('role_2_x_position');
        });
    }
};
