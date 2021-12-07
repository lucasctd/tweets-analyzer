<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city', function (Blueprint $table) {
            $table->integer('codigo');//IBGE
            $table->string('nome', 150);
            $table->integer('codigo_uf');
            $table->float('latitude', 16, 10);
            $table->float('longitude', 16, 10);
            $table->primary('codigo');

            $table->foreign('codigo_uf')->references('codigo')->on('br_state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('city');
    }
}
