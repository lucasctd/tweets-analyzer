<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddUniqueTextTweetOwner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE tweet ADD CONSTRAINT UC_TEXT_OWNER UNIQUE (text,tweet_owner);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE tweet DROP INDEX UC_TEXT_OWNER;');
    }
}
