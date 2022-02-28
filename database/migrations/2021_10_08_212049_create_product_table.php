<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->increments('product_id');
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->text('product_description')->nullable();
            $table->string('product_image')->nullable();
            $table->integer('product_buy');
            $table->integer('product_sell');
            $table->integer('product_price');
            $table->integer('product_category_id')->nullable();
            $table->integer('product_supplier_id')->nullable();
            $table->string('product_tax_code')->nullable();
            $table->integer('product_material_id')->nullable();
            $table->integer('product_unit_code')->nullable();
            $table->integer('product_size_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
}
