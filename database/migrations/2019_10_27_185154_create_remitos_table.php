<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemitosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remitos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('numero');
            $table->date('fecha');
            $table->unsignedBigInteger('comedor_id');
            $table->foreign('comedor_id')->references('id')->on('comedores');
            $table->unsignedBigInteger('proveedor_id');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
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
        Schema::dropIfExists('remitos');
    }
}
