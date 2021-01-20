<?php

namespace App\Http\Resources;

use App\Http\Collections\ApiPaginatedCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\GamePlayRepository;
use Illuminate\Http\Request;
use App\Http\Collections\PlayerGamesCollection;

class PlayerWithGamesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return PlayerResource::make($this->resource)->toArray($request) + [
            "games" => PlayerGamesCollection::make($this->games(), $this->resource),
            "player_games" => $this->playerGames()
        ];
    }

    /**
     * Get Apipaginated collection for player games
     * 
     * @return App\Http\Collections\ApiPaginatedCollection
     */
    private function playerGames(): ApiPaginatedCollection
    {
        $request = new Request(['initiator' => $this->id]);
        return ApiPaginatedCollection::make(
            GamePlayRepository::make()->listGamePlaysBuilder($request)->latest()->paginate()
                ->withPath(route('api-v1-game-plays-index'))
                ->appends(['initiator' => $this->id]),
            GamePlayResource::class
        );
    }
}
