<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamePlayPlayer extends Model
{
    use HasFactory;

    /**
     * GamePlayPlayer belong to a GamePlay relation
     * 
     * @return mixed
     */
    public function gamePlay()
    {
        return $this->belongsTo(GamePlay::class);
    }

    /**
     * GamePlayPlayer belong to a Player relation
     * 
     * @return mixed
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
