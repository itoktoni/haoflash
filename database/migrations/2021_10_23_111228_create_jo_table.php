<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jo', function (Blueprint $table) {
            $table->string('jo_code')->primary();
            $table->dateTime('jo_created_at')->nullable();
            $table->dateTime('jo_updated_at')->nullable();
            $table->dateTime('jo_deleted_at')->nullable();
            $table->integer('jo_created_by')->nullable();
            $table->integer('jo_updated_by')->nullable();
            $table->integer('jo_deleted_by')->nullable();
            $table->integer('jo_customer_id')->nullable();
            $table->string('jo_code_invoice')->nullable();
            $table->tinyInteger('jo_status')->nullable();
            $table->text('jo_notes')->nullable();
            $table->string('jo_discount_name')->nullable();
            $table->integer('jo_discount_value')->nullable();
            $table->bigInteger('jo_sum_value')->nullable();
            $table->integer('jo_sum_discount')->nullable();
            $table->bigInteger('jo_sum_total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jo');
    }
}
