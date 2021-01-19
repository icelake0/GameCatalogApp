<?php

namespace App\Http\Resources;

use App\Models\GamePlay;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "nickname" => $this->nickname,
            "date_joined" => $this->date_joined,
            "last_login" => $this->last_login,
            // "game_play" => GamePlay::paginate()->toArray()['data'],
        ];
    }
}
