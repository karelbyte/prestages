<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCacheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('comb_id')->nullable();
            $table->string('codebar')->nullable();
            $table->string('upc')->nullable();
            $table->string('ean13')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->double('price')->nullable();
            $table->string('reference')->nullable();
            $table->double('amount')->nullable();
            $table->double('discount')->nullable();
            $table->double('full_price')->nullable();
            $table->double('unit_price')->nullable();
            $table->double('tax')->nullable();
            $table->double('specific_price')->nullable();
            $table->double('wholesale_price')->nullable();
            $table->string('category')->nullable();
            $table->integer('attribute_id')->nullable();
            $table->integer('min_stock')->nullable();
            $table->integer('min_order')->nullable();
            $table->integer('order_amount')->nullable();
            $table->integer('order_received')->nullable();
            $table->integer('stock_id')->nullable();
            $table->integer('in_stock')->nullable();
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
        Schema::drop('cache');
    }
}
