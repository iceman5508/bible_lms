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
        Schema::table('scorm', function (Blueprint $table) {
            $table->string('duration')->nullable()->after('version');
            $table->double('duration_in_second')->default(0)->after('duration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scorm', function (Blueprint $table) {
            $table->dropColumn('duration');
            $table->dropColumn('duration_in_second');
        });
    }
};
