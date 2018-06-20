<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollumnSentimentTweetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tweet', function (Blueprint $table) {
            $table->unsignedInteger('sentiment_id')->unique()->nullable();
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
        Schema::table('tweet', function (Blueprint $table) {
            $table->dropForeign(['sentiment_id']);
            $table->dropColumn('sentiment_id');
        });
    }
}
