<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsistenciaSancionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistencia_sancion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('asistencia_id');
            $table->foreign('asistencia_id')->references('id')->on('asistencias');
            $table->unsignedBigInteger('sancion_id');
            $table->foreign('sancion_id')->references('id')->on('sanciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asistencia_sancion');
    }
}
