<?php

namespace App\Http\Collections;

use App\Http\Resources\GamePlayResource;
use App\Http\Resources\GameResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use App\Models\Player;
use App\Repositories\GamePlayRepository;
use Illuminate\Http\Request;
use App\Models\Game;

class PlayerGamesCollection extends ResourceCollection
{
    /**
     * Player instance the game collection belongs to
     * 
     * @var App\Models\Player
     */
    protected $player;

    /**
     * Response data without meta
     * 
     * @var Illuminate\Support\Collection
     */
    protected $response;

    /**
     * Class constructor
     * 
     * @param Illuminate\Support\Collection $resource 
     * @param App\Models\Player $player
     */
    public function __construct(Collection $resource, Player $player)
    {
        parent::__construct($resource);
        $this->player = $player;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request|null  $request
     * @return Illuminate\Support\Collection
     */
    public function toArray($request = null): Collection
    {
        return $this->resource->transform(function ($game) {
            return collect([
                'game' => GameResource::make($game),
                'game_plays' => $this->getPlayerGamePlayApiPaginatedCollection($game)
            ]);
        });
    }

    /**
     * Get Player GamePlay ApiPaginatedCollection
     * 
     * @param App\Models\Game $game
     * @return ApiPaginatedCollection
     */
    public function getPlayerGamePlayApiPaginatedCollection(Game $game): ApiPaginatedCollection
    {
        $request = new Request([
            'initiator' => $this->player->id,
            'game' => $game->id
        ]);
        return ApiPaginatedCollection::make(
            GamePlayRepository::make()->listGamePlaysBuilder($request)->latest()->paginate()
                ->withPath(route('api-v1-game-plays-index'))
                ->appends(['initiator' => $this->player->id, 'game' => $game->id]),
            GamePlayResource::class
        );
    }
}
