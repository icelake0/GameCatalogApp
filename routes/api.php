<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => '/v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::get('/games', ['uses' => 'GamesController@index', 'as' => 'api-v1-games-index']);
    Route::get('/players', ['uses' => 'PlayersController@index', 'as' => 'api-v1-players-index']);
    Route::get('/game-plays', ['uses' => 'GamePlaysController@index', 'as' => 'api-v1-game-plays-index']);
    Route::get('/players/top-100/{month}', ['uses' => 'PlayersController@top100', 'as' => 'api-v1-players-top-100']);
});

