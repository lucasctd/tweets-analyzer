<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnPrecandidatoIdHashtagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hashtag', function (Blueprint $table) {
            $table->dropForeign(['precandidato_id']);
            $table->dropIndex('hashtag_precandidato_id_foreign');
            $table->renameColumn('precandidato_id', 'filter_id');
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
            $table->renameColumn('filter_id', 'precandidato_id');
            $table->foreign('precandidato_id')->references('id')->on('precandidato');
        });
    }
}
