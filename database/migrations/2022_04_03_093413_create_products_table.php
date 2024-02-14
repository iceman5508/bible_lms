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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('user_id');
            $table->string('name');
            $table->mediumText('details');
            $table->mediumText('book_summery')->nullable();
            $table->decimal('price')->default(0.00);
            $table->string('image')->nullable();
            $table->string('summery_file')->nullable();
            $table->string('main_file')->nullable();
            $table->string('type')->comment('ebook, hard_copy');
            $table->string('slug');
            $table->tinyInteger('status')->default(0)->comment('1=approved, 0=unapproved');
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
        Schema::dropIfExists('products');
    }
};
