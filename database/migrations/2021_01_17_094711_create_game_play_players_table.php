<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamePlayPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_play_players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_play_id')->index();
            $table->foreign('game_play_id')->references('id')->on('game_plays')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->unsignedBigInteger('player_id')->index();
            $table->foreign('player_id')->references('id')->on('players')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->float('score');
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
        Schema::dropIfExists('game_play_players');
    }
}
