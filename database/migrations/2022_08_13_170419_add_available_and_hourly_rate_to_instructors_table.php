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
        Schema::table('instructors', function (Blueprint $table) {
            $table->tinyInteger('consultation_available')->after('status')->default(0)->comment('1=yes, 0=no')->nullable();
            $table->tinyInteger('available_type')->after('consultation_available')->default(3)->comment('1=In-person, 0=Online, 3=Both')->nullable();
            $table->integer('hourly_rate')->after('consultation_available')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instructors', function (Blueprint $table) {
            $table->dropColumn('consultation_available');
            $table->dropColumn('hourly_rate');
        });
    }
};
