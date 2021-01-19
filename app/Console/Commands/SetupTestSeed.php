<?php

namespace App\Console\Commands;

use App\Jobs\SeedJobs\SeedGamePlayJob;
use App\Jobs\SeedJobs\SeedPlayersJob;
use Illuminate\Console\Command;
use App\Models\Game;
use App\Models\Version;

class SetupTestSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:test-seed';

    /**
     * A collection of versions ids
     * 
     * @var Illuminate\Support\Collection
     */
    private $versions;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'seed game catalog data for a test environment';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->now = now()->toDateTimeString();
        $this->versions = collect([]);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->seedVersions();
        $this->seedGames();
        $this->seedPlayersAndGamePlays();
        return 0;
    }

    /**
     * Seed versions table
     * 
     * @return void
     */
    private function seedVersions()
    {
        foreach (config('app.game_versions') as $version) {
            $this->versions->push(
                Version::firstOrCreate($version)->id
            );
        }
    }

    /**
     * Seed games table
     * 
     * @return void
     */
    private function seedGames()
    {
        foreach (config('app.games') as $game) {
            Game::firstOrCreate($game)
                ->versions()
                ->sync($this->versions);
        }
    }

    /**
     * Seed dispatch job to seed players and game play tables
     * 
     * @return void
     */
    private function seedPlayersAndGamePlays()
    {
        SeedPlayersJob::dispatch(config('app.test_players_count'));
    }
}
