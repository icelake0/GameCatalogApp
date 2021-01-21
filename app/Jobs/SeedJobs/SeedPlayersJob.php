<?php

namespace App\Jobs\SeedJobs;

use App\Models\GameVersion;
use App\Models\Player;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SeedPlayersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param int $total number of Players to seed
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('players');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->seedPlayers();
    }

    /**
     * Seed players with factory
     * 
     * @return void
     */
    private function seedPlayers()
    {
        Player::factory()
            ->count(1)
            ->hasAttached(
                $this->getRandoGameVersions()
            )->create();
    }

    /**
     * Get random game versions for player
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    private function getRandoGameVersions(): Collection
    {
        return  GameVersion::inRandomOrder()
            ->limit(
                rand(1, 55)
            )->get();
    }
}
