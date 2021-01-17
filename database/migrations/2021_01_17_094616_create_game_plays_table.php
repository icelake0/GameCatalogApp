<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamePlaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_plays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('initiator_id')->index();
            $table->foreign('initiator_id')->references('id')->on('players')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->unsignedBigInteger('game_version_id')->index();
            $table->foreign('game_version_id')->references('id')->on('game_versions')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->date('time_played');
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
        Schema::dropIfExists('game_plays');
    }
}
