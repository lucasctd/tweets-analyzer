<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPrecandidatoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hashtag', function (Blueprint $table) {
            $table->unsignedInteger('precandidato_id')->nullable();
            $table->foreign('precandidato_id')->references('id')->on('precandidato');

            $table->bigInteger('tweet_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hashtag', function (Blueprint $table) {
            $table->dropForeign(['precandidato_id']);
            $table->dropColumn('precandidato_id');

            $table->bigInteger('tweet_id')->change();
        });
    }
}
