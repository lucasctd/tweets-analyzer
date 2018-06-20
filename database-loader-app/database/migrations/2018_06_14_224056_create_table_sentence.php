<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSentence extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sentence', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text', 1000);
            $table->decimal('score', 3, 2);
            $table->decimal('magnitude', 12, 2);

            $table->unsignedInteger('sentiment_id');

            $table->foreign('sentiment_id')->references('id')->on('sentiment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sentence');
    }
}
