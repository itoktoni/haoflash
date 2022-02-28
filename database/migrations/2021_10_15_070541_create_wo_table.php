<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wo', function (Blueprint $table) {
            $table->string('wo_id')->autoIncrement();
            $table->string('wo_so_code')->nullable();
            $table->dateTime('wo_created_at')->nullable();
            $table->dateTime('wo_updated_at')->nullable();
            $table->dateTime('wo_processed_at')->nullable();
            $table->dateTime('wo_delivered_at')->nullable();
            $table->dateTime('wo_deleted_at')->nullable();
            $table->integer('wo_created_by')->nullable();
            $table->integer('wo_updated_by')->nullable();
            $table->integer('wo_deleted_by')->nullable();
            $table->integer('wo_customer_id')->nullable();
            $table->string('wo_code_delivery')->nullable();
            $table->string('wo_code_invoice')->nullable();
            $table->tinyInteger('wo_status')->nullable();
            $table->text('wo_notes_internal')->nullable();
            $table->text('wo_notes_external')->nullable();
            $table->string('wo_discount_name')->nullable();
            $table->integer('wo_discount_value')->nullable();
            $table->bigInteger('wo_sum_value')->nullable();
            $table->integer('wo_sum_discount')->nullable();
            $table->bigInteger('wo_sum_total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wo');
    }
}
