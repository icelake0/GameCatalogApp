<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Collections\ApiPaginatedCollection;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Http\Resources\Top100PlayerResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\PlayerRepository;
use App\Repositories\GamePlayPlayerRepository;
use App\Http\Responser;

class PlayersController extends Controller
{
    /**
     * Injected instance of player repository
     * 
     * @var App\Repositories\PlayerRepository 
     */
    protected $player_repository;

    /**
     * Injected instance of game play player repository
     * 
     * @var App\Repositories\GamePlayPlayerRepository 
     */
    protected $game_play_player_repository;

    /**
     * Class constructor
     * 
     * @param App\Repositories\PlayerRepository $player_repository
     * @param App\Repositories\GamePlayPlayerRepository $game_play_player_repository
     */
    public function __construct(
        PlayerRepository $player_repository,
        GamePlayPlayerRepository $game_play_player_repository
    ) {
        $this->player_repository = $player_repository;
        $this->game_play_player_repository = $game_play_player_repository;
    }

    /**
     * List All Players
     * 
     * @param Request $request
     * @return Illuminate\Http\JsonResponse;
     */
    public function index(Request $request): JsonResponse
    {
        $data = ApiPaginatedCollection::make(
            $this->player_repository->listPlayers($request),
            PlayerResource::class
        );
        return Responser::sendPaginated(200, $data, 'Listing all players with pagination');
    }

    /**
     * List top 100 players
     * 
     * @param Request $request
     * @return Illuminate\Http\JsonResponse;
     */
    public function top100(Request $request, string $month): JsonResponse
    {
        $data = Top100PlayerResource::collection(
            $this->game_play_player_repository->listTop100GamePlayPlayers($request, $month)
        );
        return Responser::send(200, $data, "Listing top 100 players for {$month}");
    }
}
