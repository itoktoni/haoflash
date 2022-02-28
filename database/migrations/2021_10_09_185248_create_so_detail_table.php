<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_detail', function (Blueprint $table) {
            $table->string('so_detail_code')->primary();
            $table->string('so_detail_so_code')->nullable();
            $table->string('so_detail_notes')->nullable();
            $table->integer('so_detail_supplier_id')->nullable();
            $table->integer('so_detail_product_id')->nullable();
            $table->integer('so_detail_product_price')->nullable();
            $table->integer('so_detail_qty')->nullable();
            $table->integer('so_detail_price')->nullable();
            $table->integer('so_detail_total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
