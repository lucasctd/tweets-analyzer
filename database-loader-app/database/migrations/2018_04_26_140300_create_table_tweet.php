<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTweet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweet', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('tweet_id');
            $table->string('id_str', 20);
            $table->string('text', 280);
            $table->string('tweet_owner', 100);
            $table->integer('favorite_count')->nullable();
            $table->integer('retweet_count');
            $table->integer('reply_count')->nullable();
            $table->integer('quote_count')->nullable();
            $table->string('url', 150);
			$table->timestamp('tweet_created_at');
			$table->timestamp('created_at')->useCurrent();
			
			$table->integer('pla_id')->unsigned()->nullable();
			$table->foreign('pla_id')->references('id')->on('place');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tweet');
    }
}
