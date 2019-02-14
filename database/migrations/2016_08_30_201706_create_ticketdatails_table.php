<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketdatailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticketdetails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idticket');
            $table->integer('iduser');
            $table->string('ean13');
            $table->string('name');
            $table->double('cant');
            $table->double('price');
            $table->double('iva');
            $table->double('ivaimport');
            $table->double('discount');
            $table->double('fullimport');
            $table->integer('stock_id');
            $table->integer('status');
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
        Schema::drop('ticketdetails');
    }
}
