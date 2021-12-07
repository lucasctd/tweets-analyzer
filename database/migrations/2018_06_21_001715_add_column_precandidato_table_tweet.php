<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPrecandidatoTableTweet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tweet', function (Blueprint $table) {
            $table->unsignedInteger('precandidato_id')->nullable();
            $table->foreign('precandidato_id')->references('id')->on('precandidato');
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
            $table->dropForeign(['precandidato_id']);
            $table->dropColumn('precandidato_id');
        });
    }
}
