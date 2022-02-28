<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so', function (Blueprint $table) {
            $table->string('so_code')->primary();
            $table->dateTime('so_created_at')->nullable();
            $table->dateTime('so_updated_at')->nullable();
            $table->dateTime('so_processed_at')->nullable();
            $table->dateTime('so_delivered_at')->nullable();
            $table->dateTime('so_deleted_at')->nullable();
            $table->integer('so_created_by')->nullable();
            $table->integer('so_updated_by')->nullable();
            $table->integer('so_deleted_by')->nullable();
            $table->integer('so_customer_id')->nullable();
            $table->string('so_code_delivery')->nullable();
            $table->string('so_code_invoice')->nullable();
            $table->tinyInteger('so_status')->nullable();
            $table->text('so_notes_internal')->nullable();
            $table->text('so_notes_external')->nullable();
            $table->string('so_discount_name')->nullable();
            $table->integer('so_discount_value')->nullable();
            $table->bigInteger('so_sum_value')->nullable();
            $table->integer('so_sum_discount')->nullable();
            $table->bigInteger('so_sum_total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('so');
    }
}
