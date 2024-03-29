<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comedores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descripcion');
            $table->string('direccion');
            $table->unsignedBigInteger('unidad_academica_id');
            $table->foreign('unidad_academica_id')->references('id')->on('unidades_academicas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comedores');
    }
}
