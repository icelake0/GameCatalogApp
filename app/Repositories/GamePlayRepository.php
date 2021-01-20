<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\GamePlay;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GamePlayRepository
{
    /**
     * GamePlay instancr
     * 
     * @var GamePlay
     */
    protected $game_play;

    /**
     * QuaryBuilder instance
     * 
     * @var Builder
     */
    protected $builder;

    /**
     * Class constructor
     * 
     * @param App\Models\GamePlay $game
     */
    public function __construct(GamePlay $game_play)
    {
        $this->game_play = $game_play;
        $this->newBuilder();
    }

    /**
     * make a new instance of GamePlayRepository
     * 
     * @return GamePlayRepository
     */
    public static function make(): GamePlayRepository
    {
        return new static(new GamePlay);
    }

    /**
     * Setup new builder on the repository instance
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function newBuilder(): Builder
    {
        return $this->builder = $this->game_play->newQuery();
    }

    /**
     * List GamePlays with filters from request
     * 
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listGamePlays(Request $request): LengthAwarePaginator
    {
        return $this->listGamePlaysBuilder($request)->latest()->paginate();
    }

    /**
     * Get builder for List GamePlays with filters from request
     * 
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function listGamePlaysBuilder(Request $request): Builder
    {
        $this->newBuilder();
        $this->fiterByDatePlayed($request->date_played ?? null);
        $this->fiterByDateRange(
            $request->date_played_range[0] ?? null,
            $request->date_played_range[1] ?? null
        );
        $this->fiterByInitiator($request->initiator);
        $this->filterByGame($request->game);
        return $this->builder;
    }

    /**
     * Add date played range filter to query 
     * 
     * @param string|null $start_date
     * @param string|null $end_date 
     * @return void
     */
    protected function fiterByDateRange(string $start_date =  null, string $end_date = null)
    {
        if ($start_date && $end_date) {
            $this->builder->whereBetween(
                'time_played',
                [$start_date, $end_date]
            );
        }
    }

    /**
     * Add date played filter to query 
     * 
     * @param string|null $date_played
     * @return void
     */
    protected function fiterByDatePlayed(string $date_played = null)
    {
        if ($date_played) {
            $this->builder->whereRaw("DATE_FORMAT(time_played, '%Y-%m-%d') = '{$date_played}'");
        }
    }

    /**
     * Add initiator filter to query 
     * 
     * @param int|null $initiator_id
     * @return void
     */
    protected function fiterByInitiator(int $initiator_id = null)
    {
        if ($initiator_id) {
            $this->builder->where('initiator_id', $initiator_id);
        }
    }

    /**
     * Add game filter to query 
     * 
     * @param int|null $game_id
     * @return void
     */
    protected function filterByGame(int $game_id = null)
    {
        if ($game_id) {
            $this->builder->whereHas('gameVersion', function ($q) use ($game_id) {
                $q->where('game_id', $game_id);
            });
        }
    }
}
