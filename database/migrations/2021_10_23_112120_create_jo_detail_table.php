<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJoDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jo_detail', function (Blueprint $table) {
            $table->string('jo_detail_code')->primary();
            $table->string('jo_detail_jo_code')->nullable();
            $table->string('jo_detail_notes')->nullable();
            $table->integer('jo_detail_supplier_id')->nullable();
            $table->integer('jo_detail_product_id')->nullable();
            $table->integer('jo_detail_product_price')->nullable();
            $table->integer('jo_detail_qty')->nullable();
            $table->integer('jo_detail_price')->nullable();
            $table->integer('jo_detail_total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jo_detail');
    }
}
