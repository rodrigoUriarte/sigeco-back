<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInscripcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha_inscripcion');
            $table->boolean('retira');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('banda_horaria_id');
            $table->foreign('banda_horaria_id')->references('id')->on('bandas_horarias');
            $table->unsignedBigInteger('menu_asignado_id');
            $table->foreign('menu_asignado_id')->references('id')->on('menus_asignados');
            $table->unsignedBigInteger('comedor_id');
            $table->foreign('comedor_id')->references('id')->on('comedores');
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
        Schema::dropIfExists('inscripciones');
    }
}
