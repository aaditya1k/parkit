<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParkingLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parking_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label');
            $table->integer('parking_id')->unsigned();
            $table->tinyInteger('grid_row');
            $table->tinyInteger('grid_col');
            $table->text('grid_map');
            $table->string('generated_map')->nullable();
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
        Schema::dropIfExists('parking_levels');
    }
}
