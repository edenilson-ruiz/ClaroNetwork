<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatapointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datapoints', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cell_asu_level')->nullable();
            $table->integer('cell_signal_strength')->nullable();
            $table->integer('cell_signal_strength_dbm')->nullable();
            $table->string('data_activity')->nullable();
            $table->string('data_state')->nullable();
            $table->string('detailed_state')->nullable();
            $table->double('device_latitude')->nullable();
            $table->double('device_longitude')->nullable();
            $table->float('device_position_accuracy')->nullable();
            $table->float('device_speed')->nullable();
            $table->string('download_speed')->nullable();
            $table->string('extra_info')->nullable();
            $table->string('http_connection_test')->nullable();
            $table->string('is_available')->nullable();
            $table->string('is_connected')->nullable();
            $table->string('is_failover')->nullable();
            $table->string('is_network_metered')->nullable();
            $table->string('is_roaming')->nullable();
            $table->string('mobile_data_network_type')->nullable();
            $table->integer('network_mcc')->nullable();
            $table->integer('network_mnc')->nullable();
            $table->string('network_operator')->nullable();
            $table->string('network_type')->nullable();
            $table->string('service_state')->nullable();
            $table->integer('sim_mcc')->nullable();
            $table->integer('sim_mnc')->nullable();
            $table->string('sim_operator')->nullable();
            $table->string('sim_state')->nullable();
            $table->string('socket_connection_test')->nullable();
            $table->string('timestamp')->nullable();
            $table->string('uid')->nullable();
            $table->string('upload_speed')->nullable();
            $table->string('address_location')->nullable();
            $table->string('departamento')->nullable();
            $table->string('municipio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datapoints');
    }
}
