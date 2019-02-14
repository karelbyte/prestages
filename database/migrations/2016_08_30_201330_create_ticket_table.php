<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('codeclient');
            $table->double('fullimport');
            $table->double('received');
            $table->double('change');
            $table->enum('typepayment', ['cash', 'credit']);
            $table->integer('status');
            $table->integer('idclose');
            $table->string('owner')->nullable();
            $table->integer('idowner')->nullable();
            $table->integer('gift')->nullable();
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
        Schema::drop('tickets');
    }
}
