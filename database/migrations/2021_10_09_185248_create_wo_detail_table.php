<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWoDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wo_detail', function (Blueprint $table) {
            $table->string('wo_detail_code')->primary();
            $table->string('wo_detail_wo_code')->nullable();
            $table->string('wo_detail_notes')->nullable();
            $table->integer('wo_detail_product_id')->nullable();
            $table->integer('wo_detail_product_price')->nullable();
            $table->integer('wo_detail_qty')->nullable();
            $table->integer('wo_detail_price')->nullable();
            $table->integer('wo_detail_total')->nullable();
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
