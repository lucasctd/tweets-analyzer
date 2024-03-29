<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTableHashtagUsernameToHashtagAndDropUsernameColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hashtag_username', function (Blueprint $table) {
            $table->dropColumn(['username']);
        });
        Schema::rename('hashtag_username', 'hashtag');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hashtag', function (Blueprint $table) {
			$table->boolean('username');
        });
        Schema::rename('hashtag', 'hashtag_username');
    }
}
