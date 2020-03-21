<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsistenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('fecha_asistencia')->nullable();
            $table->boolean('asistio');
            $table->boolean('asistencia_fbh');
            $table->unsignedBigInteger('inscripcion_id');
            $table->foreign('inscripcion_id')->references('id')->on('inscripciones');
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
        Schema::dropIfExists('asistencias');
    }
}
