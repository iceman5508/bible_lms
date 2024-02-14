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
        Schema::table('ranking_levels', function (Blueprint $table) {
            $table->tinyInteger('type')->after('name')->nullable();
            $table->decimal('from', 10, 2)->after('type')->default(0.00)->nullable();
            $table->decimal('to', 10, 2)->after('from')->default(0.00)->nullable();
            $table->string('description')->after('to')->nullable();
        
            $table->string('name')->nullable()->change();
            $table->string('badge_image')->nullable()->change();
            $table->integer('earning')->nullable()->change();
            $table->integer('student')->nullable()->change();
            $table->integer('serial_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ranking_levels', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('from');
            $table->dropColumn('to');
            $table->dropColumn('description');
        });
    }
};
