<?php

namespace App\Repositories;

use App\Models\GamePlayPlayer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class GamePlayPlayerRepository
{
    /**
     * GamePlayPlayer instancr
     * 
     * @var GamePlayPlayer
     */
    protected $game_play_player;

    /**
     * QuaryBuilder instance
     * 
     * @var Builder
     */
    protected $builder;

    /**
     * Class constructor
     * 
     * @param App\Models\GamePlayPlayer $game_play_player
     */
    public function __construct(GamePlayPlayer $game_play_player)
    {
        $this->game_play_player = $game_play_player;
        $this->newBuilder();
    }

    /**
     * Setup new builder on the repository instance
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function newBuilder(): Builder
    {
        return $this->builder = $this->game_play_player->newQuery();
    }

    /**
     * Get builder for List GamePlayPlayers with filters from request
     * 
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function listGamePlayPlayersBuilder(Request $request): Builder
    {
        $this->newBuilder();
        $this->filterByMonth($request->month_filter);
        return $this->builder;
    }

    /**
     * List top 100 Players for month with filters from request
     * 
     * @param Illuminate\Http\Request $request
     * @param string $month
     * @return Illuminate\Support\Collection
     */
    public function listTop100GamePlayPlayers(Request $request, string $month): Collection
    {
        $request->merge(['month_filter' => $month]);
        return $this->listGamePlayPlayersBuilder($request)
            ->select('player_id', \DB::raw('sum(score) as score'), \DB::raw("'{$month}' as month"))
            ->with('player')
            ->groupBy('player_id')
            ->orderBy('score', 'desc')
            ->limit(100)
            ->get();
    }

    /**
     * Add month played filter to query 
     * 
     * @param string|null $date_played
     * @return void
     */
    protected function filterByMonth(string $month = null)
    {
        if ($month) {
            try {
                $month = Carbon::parse($month);
            } catch (\Exception $e) {
                $month = Carbon::today();
            }
            $month = "{$month->year}-{$month->month}";
            $this->builder->whereHas('gamePlay', function ($q) use ($month) {
                $q->whereRaw("DATE_FORMAT(time_played, '%Y-%c') = '{$month}'");
            });
        }
    }
}
