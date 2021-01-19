<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Collections\ApiPaginatedCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\GamePlayRepository;
use App\Http\Resources\GamePlayResource;
use App\Http\Responser;

class GamePlaysController extends Controller
{
    /**
     * Injected instance of game play repository
     * 
     * @var App\Repositories\GamePlayRepository 
     */
    protected $game_play_repository;

    /**
     * Class constructor
     * 
     * @param App\Repositories\GamePlayRepository $game_play_repository
     */
    public function __construct(
        GamePlayRepository $game_play_repository
    ) {
        $this->game_play_repository = $game_play_repository;
    }

    /**
     * List Game Plays
     * 
     * @param Request $request
     * @return Illuminate\Http\JsonResponse;
     */
    public function index(Request $request): JsonResponse
    {
        $data = ApiPaginatedCollection::make(
            $this->game_play_repository->listGamePlays($request),
            GamePlayResource::class
        );
        return Responser::sendPaginated(200, $data, 'Listing game plays with pagination');
    }
}
