<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParkingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parkings', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned();
            $table->string('label');
            $table->string('secret_key');
            $table->boolean('manual_parkno');
            $table->tinyInteger('bike_charge_method');
            $table->string('bike_charge_json');
            $table->float('bike_charge_max');
            $table->tinyInteger('car_charge_method');
            $table->string('car_charge_json');
            $table->float('car_charge_max');
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
        Schema::dropIfExists('parkings');
    }
}
