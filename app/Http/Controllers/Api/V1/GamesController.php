<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Collections\ApiPaginatedCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\GameRepository;
use App\Http\Resources\GameWithVersionsResource;
use App\Http\Responser;

class GamesController extends Controller
{
    /**
     * Injected instance of game repository
     * 
     * @var App\Repositories\GameRepository 
     */
    protected $game_repository;

    /**
     * Class constructor
     * 
     * @param App\Repositories\GameRepository $game_repository,
     */
    public function __construct(
        GameRepository $game_repository
    ) {
        $this->game_repository = $game_repository;
    }

    /**
     * List All Games
     * 
     * @param Request $request
     * @return Illuminate\Http\JsonResponse;
     */
    public function index(Request $request): JsonResponse
    {
        $data = ApiPaginatedCollection::make(
            $this->game_repository->listGames($request),
            GameWithVersionsResource::class
        );
        return Responser::sendPaginated(200, $data, 'Listing games with pagination');
    }
}
