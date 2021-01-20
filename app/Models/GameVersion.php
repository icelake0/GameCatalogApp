<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameVersion extends Model
{
    use HasFactory;

    /**
     * Game version belong to a game relation
     * 
     * @return mixed
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Game version belong to a version relation
     * 
     * @return mixed
     */
    public function version()
    {
        return $this->belongsTo(Version::class);
    }


    /**
     * Game version has many players relation
     * 
     * @return mixed
     */
    public function players()
    {
        return $this->belongsToMany(Player::class, 'player_game_versions');
    }
}
