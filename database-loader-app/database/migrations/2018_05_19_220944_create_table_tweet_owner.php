<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTweetOwner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //https://developer.twitter.com/en/docs/tweets/data-dictionary/overview/user-object
        Schema::create('tweet_owner', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id');
            $table->string('id_str', 50);
            $table->string('name', 100);
            $table->string('screen_name', 100)->unique();
            $table->string('location', 100)->nullable();
            $table->string('url')->nullable();
            $table->string('description')->nullable();
            $table->integer('followers_count');
            $table->integer('friends_count');
            $table->integer('favourites_count');
            $table->integer('statuses_count');
            $table->integer('user_created_at');
            $table->boolean('geo_enabled');
            $table->timestamp('created_at')->useCurrent();

            $table->integer('city_id')->nullable();
            $table->integer('br_state_id')->nullable();

            $table->foreign('city_id')->references('codigo')->on('city');
            $table->foreign('br_state_id')->references('codigo')->on('br_state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tweet_owner');
    }
}
