<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha_vencimiento');
            $table->float('cantidad');
            $table->boolean('usado');
            $table->unsignedBigInteger('insumo_remito_id');
            $table->foreign('insumo_remito_id')->references('id')->on('insumo_remito');
            $table->unsignedBigInteger('comedor_id');
            $table->foreign('comedor_id')->references('id')->on('comedores');
            $table->unsignedBigInteger('insumo_id');
            $table->foreign('insumo_id')->references('id')->on('insumos');
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
        Schema::dropIfExists('lotes');
    }
}
