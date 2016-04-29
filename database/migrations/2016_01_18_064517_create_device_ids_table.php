<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_ids', function (Blueprint $table) {
            $table->increments('id');
            $table->string('registration_id')->unique();
            $table->integer('user_id');
            $table->string('deviceType')->nullable();
            $table->timestamps();

            $table->unique(['registration_id', 'user_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('device_ids');
    }
}