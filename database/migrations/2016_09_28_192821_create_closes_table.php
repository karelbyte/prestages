<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('closes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('iduser');
            $table->timestamp('creado');
            $table->timestamp('fecha_inicio');
            $table->timestamp('fecha_final');
            $table->double('inicio_caja');
            $table->double('total_caja');
            $table->double('devolucion_credito');
            $table->double('devolucion_efectivo');
            $table->double('efectivo');
            $table->double('tarjeta');
            $table->integer('cantticket');
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
        Schema::drop('closes');
    }
}
