<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Player;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PlayerRepository
{
    /**
     * Player instancr
     * 
     * @var Player
     */
    protected $player;

    /**
     * QuaryBuilder instance
     * 
     * @var Builder
     */
    protected $builder;

    /**
     * Class constructor
     * 
     * @param App\Models\Player $player
     */
    public function __construct(Player $player)
    {
        $this->player = $player;
        $this->newBuilder();
    }

    /**
     * Setup new builder on the repository instance
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function newBuilder(): Builder
    {
        return $this->builder = $this->player->newQuery();
    }

    /**
     * Get builder for List Players with filters from request
     * 
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function listPlayersBuilder(Request $request): Builder
    {
        $this->newBuilder();
        return $this->builder;
    }

    /**
     * List Players with filters from request
     * 
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listPlayers(Request $request): LengthAwarePaginator
    {
        return $this->listPlayersBuilder($request)->latest()->paginate();
    }
}
