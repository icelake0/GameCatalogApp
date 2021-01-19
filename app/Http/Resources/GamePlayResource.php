<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GamePlayResource extends JsonResource
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
            "initiator" => PlayerResource::make($this->initiator),
            "game_version" => GameVersionResource::make($this->gameVersion),
            "time_played" => $this->time_played,
            "players" => PlayerResource::collection($this->players)
        ];
    }
}
