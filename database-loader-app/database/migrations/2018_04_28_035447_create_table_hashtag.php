<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHashtag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hashtag', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->boolean('primary');

            $table->integer('tweet_id')->unsigned();
            $table->foreign('tweet_id')->references('id')->on('tweet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hashtag');
    }
}
