<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitoringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitoring', function (Blueprint $table) {
            $table->increments('monitoring_id');
            $table->tinyInteger('monitoring_status');
            $table->text('monitoring_description')->nullable();
            $table->dateTime('monitoring_created_at')->nullable();
            $table->dateTime('monitoring_updated_at')->nullable();
            $table->dateTime('monitoring_deleted_at')->nullable();
            $table->integer('monitoring_created_by')->nullable();
            $table->integer('monitoring_updated_by')->nullable();
            $table->integer('monitoring_deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitoring');
    }
}
