<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
            $table->bigInteger('id');
            $table->string('id_str', 20);
            $table->string('text', 500);
            $table->integer('favorite_count')->nullable();
            $table->integer('retweet_count');
            $table->integer('reply_count')->nullable();
            $table->integer('quote_count')->nullable();
			$table->string('followers_count')->nullable();
            $table->string('url', 150);
			$table->timestamp('tweet_created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

			$table->primary('id');
			$table->index('tweet_created_at');

            $table->bigInteger('owner_id')->nullable();
            $table->foreign('owner_id')->references('id')->on('tweet_owner');
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
