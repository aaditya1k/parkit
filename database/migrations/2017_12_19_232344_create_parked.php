<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParked extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parked', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('parking_id')->unsigned();
            $table->integer('group_id')->unsigned();
            $table->integer('position')->nullable();
            $table->tinyInteger('vehicle_type');
            $table->timestamps();
            $table->float('exit_charges')->nullable();
            $table->datetime('exited_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('parked');
    }
}
