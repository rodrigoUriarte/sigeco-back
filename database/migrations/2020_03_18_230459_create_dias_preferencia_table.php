<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiasPreferenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dias_preferencia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('retira');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('dia_servicio_id');
            $table->foreign('dia_servicio_id')->references('id')->on('dias_servicio');
            $table->unsignedBigInteger('banda_horaria_id');
            $table->foreign('banda_horaria_id')->references('id')->on('bandas_horarias');
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
        Schema::dropIfExists('dias_preferencia');
    }
}
