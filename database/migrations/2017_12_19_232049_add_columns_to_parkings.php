<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToParkings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parkings', function (Blueprint $table) {
            $table->string('exit_generated_key')->after('secret_key');
            $table->string('entry_image')->after('manual_parkno')->nullable();
            $table->string('exit_image')->after('entry_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parkings', function (Blueprint $table) {
            $table->dropColumn('exit_generated_key');
            $table->dropColumn('entry_image');
            $table->dropColumn('exit_image');
        });
    }
}
