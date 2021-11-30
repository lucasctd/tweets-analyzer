<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEntity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->string('wikipedia_url', 250)->nullable();
            $table->string('mid')->nullable();
            $table->decimal('salience', 12, 10);

            $table->unsignedInteger('sentiment_id');

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
        Schema::dropIfExists('entity');
    }
}
