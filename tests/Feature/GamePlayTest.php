<?php

namespace Tests\Feature;

use Tests\TestCase;

class GamePlayTest extends TestCase
{
    /**
     * Test the list games endpoint
     *
     * @return void
     */
    public function testListGamesTest()
    {
        $response = $this->get(route('api-v1-games-index'));
        $response->assertStatus(200);
    }

    /**
     * Test the list players endpoint
     *
     * @return void
     */
    public function testListPlayersTest()
    {
        $response = $this->get(route('api-v1-players-index'));
        $response->assertStatus(200);
    }

    /**
     * Test the list games plays endpoint
     *
     * @return void
     */
    public function testListGamePlaysTest()
    {
        $response = $this->get(route('api-v1-game-plays-index'));
        $response->assertStatus(200);
    }

    /**
     * Test the list month top 100 players endpoint
     *
     * @return void
     */
    public function testListTop100PlayersTest()
    {
        $response = $this->get(route('api-v1-players-top-100', ['month' => 'jan-2010']));
        $response->assertStatus(200);
    }
}
