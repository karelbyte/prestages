<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXmlstoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xmlstore', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type');
            $table->integer('article_id');
            $table->longText('xml');
            $table->dateTime('update');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('xmlstore');
    }
}
