<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBandasHorariasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bandas_horarias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descripcion');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('limite_comensales');
            $table->unsignedBigInteger('comedor_id');
            $table->foreign('comedor_id')->references('id')->on('comedores');
            // $table->unique(['descripcion', 'comedor_id']); se controla en el form request
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
        Schema::dropIfExists('bandas_horarias');
    }
}
