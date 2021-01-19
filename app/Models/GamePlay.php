<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamePlay extends Model
{
    use HasFactory;

    protected $fillable = ['initiator_id', 'game_version_id', 'time_played'];

    /**
     * Game play belongs to an initiating player relation
     * 
     * @return mixed
     */
    public function initiator()
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Game play has many participating players relation
     * 
     * @return mixed
     */
    public function players()
    {
        return $this->belongsToMany(Player::class, 'game_play_players')
        ->withTimestamps()->withPivot(['score']);
    }
}
