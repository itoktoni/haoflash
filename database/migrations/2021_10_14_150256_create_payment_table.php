<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->increments('payment_id');
            $table->tinyInteger('payment_model')->nullable();
            $table->tinyInteger('payment_status')->nullable();
            $table->string('payment_bank_from')->nullable();
            $table->string('payment_bank_to')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('payment_person')->nullable();
            $table->string('payment_attachment')->nullable();
            $table->string('payment_voucher')->nullable();
            $table->string('payment_type')->nullable();
            $table->text('payment_notes_user')->nullable();
            $table->text('payment_notes_approve')->nullable();
            $table->integer('payment_value_user')->nullable();
            $table->integer('payment_value_approve')->nullable();
            $table->integer('payment_deleted_by')->nullable();
            $table->integer('payment_created_by')->nullable();
            $table->integer('payment_updated_by')->nullable();
            $table->dateTime('payment_created_at')->nullable();
            $table->dateTime('payment_updated_at')->nullable();
            $table->dateTime('payment_approved_at')->nullable();
            $table->dateTime('payment_deleted_at')->nullable();
            $table->date('payment_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
