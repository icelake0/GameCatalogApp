<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    /**
     * Player has many game versions relation
     * 
     * @return mixed
     */
    public function gameVersions()
    {
        return $this->belongsToMany(GameVersion::class, 'player_game_versions');
    }

    /**
     * Player has many gameplay relation 
     * 
     * @return mixed
     */
    public function gamePlays()
    {
        return $this->hasMany(GamePlay::class, 'initiator_id');
    }
}
