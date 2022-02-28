<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->integer('stock_customer_id')->nullable();
            $table->integer('stock_warehouse_id')->nullable();
            $table->integer('stock_location_id')->nullable();
            $table->integer('stock_product_id')->nullable();
            $table->integer('stock_qty')->nullable();
            $table->string('stock_so_code')->nullable();
            $table->string('stock_wo_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock');
    }
}
