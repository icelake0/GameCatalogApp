<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GameRepository
{
    /**
     * Game instancr
     * 
     * @var Game
     */
    protected $game;

    /**
     * QuaryBuilder instance
     * 
     * @var Builder
     */
    protected $builder;

    /**
     * Class constructor
     * 
     * @param App\Models\Game $game
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
        $this->newBuilder();
    }

    /**
     * Setup new builder on the repository instance
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function newBuilder(): Builder
    {
        return $this->builder = $this->game->newQuery();
    }

    /**
     * Get builder for List Games with filters from request
     * 
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function listGamesBuilder(Request $request): Builder
    {
        $this->newBuilder();
        return $this->builder;
    }

    /**
     * List Games with filters from request
     * 
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listGames(Request $request): LengthAwarePaginator
    {
        return $this->listGamesBuilder($request)->latest()->paginate();
    }
}
