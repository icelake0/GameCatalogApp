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
     * The total number of players to seed
     * 
     * @var int
     */
    protected $total;

    /**
     * Create a new job instance.
     *
     * @param int $total number of Players to seed
     * @return void
     */
    public function __construct(int $total)
    {
        $this->onQueue('players');
        $this->total = $total;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->seedPlayers();
        $this->seedMorePlayersIfRequired();
        $this->seedGamePlayIfPlayersAreEnough();
        $this->startSeedingGamePlayIfPlayersAreEnough();
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
                mt_rand(1, 55)
            )->get();
    }

    /**
     * Seed more players of required
     * 
     * @return void
     */
    private function seedMorePlayersIfRequired()
    {
        if ($this->needToSeedMorePlayers()) {
            static::dispatch($this->total - 1);
        }
    }

    /**
     * Check if job need to rerun for more seeds
     * 
     * @return bool
     */
    private function needToSeedMorePlayers(): bool
    {
        return $this->total > 1;
    }

    /**
     * Start seeding gameplay if we have enough players
     * 
     * @return void
     */
    private function startSeedingGamePlayIfPlayersAreEnough()
    {
        if ($this->playersAreEnough()) {
            SeedGamePlayJob::dispatch();
        }
    }

    /**
     * Check if we have enough players in the system
     * 
     * @return bool
     */
    private function playersAreEnough(): bool
    {
        return config('app.test_players_count') - $this->total == config('app.min_plays_per_day') + 10;
    }
}
