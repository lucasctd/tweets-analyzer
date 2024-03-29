<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableHashtagUsername extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hashtag_username', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->boolean('primary');
			$table->boolean('username');

            $table->bigInteger('tweet_id');
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
        Schema::dropIfExists('hashtag_username');
    }
}
