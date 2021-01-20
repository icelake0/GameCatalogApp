<?php

namespace App\Jobs\SeedJobs;

use App\Models\GamePlay;
use App\Models\GameVersion;
use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SeedGamePlayForDayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The day to seed game play for
     * 
     * @var Carbon\Carbon
     */
    private $current_day;

    /**
     * The game version in play
     * 
     * @var App\Models\GameVersion
     */
    private $game_version;

    /**
     * The game initiator
     * 
     * @var App\Models\Player
     */
    private $game_initiator;

    /**
     * The game play session
     * 
     * @var App\Models\GamePlay
     */
    private $game_play;

    /**
     * Create a new job instance.
     *
     * @param Carbon\Carbon $current_day :The day to seed game play for
     * @return void
     */
    public function __construct(Carbon $current_day)
    {
        $this->onQueue('game-play-for-day');
        $this->current_day = $current_day;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->selectGameVersion();
        $this->setGameInitiator();
        $this->seedGamePlay();
    }

    /**
     * Set the game version to be initiated
     * 
     * @return void
     */
    private function selectGameVersion()
    {
        $this->game_version = GameVersion::inRandomOrder()->first();
    }

    /**
     * Set game initiator
     * 
     * @return void
     */
    private function setGameInitiator()
    {
        $this->game_initiator =
            Player::inRandomOrder()
            ->whereHas('gameVersions', function ($q) {
                $q->where('game_versions.id', $this->game_version->id);
            })->whereDoesnthave('gamePlays', function ($q) {
                $current_day = $this->current_day->format('Y-m-d');
                $q->where('game_version_id', $this->game_version->id)
                    ->whereRaw("DATE_FORMAT(time_played, '%Y-%m-%d') = '{$current_day}'");
            })->first();
    }

    /**
     * Seed game play
     * 
     * @return void
     */
    private function seedGamePlay()
    {
        if ($this->systemIsNotReadyForgameType())
            return static::dispatch($this->current_day);
        $this->createGamePlay();
        $this->syncPlayersToGamePlay();
    }

    /**
     * Check if system is not ready for game type
     * 
     * @return bool
     */
    private function systemIsNotReadyForgameType(): bool
    {
        return !$this->game_initiator || !$this->game_version;
    }

    /**
     * Create game play session 
     * 
     * @return void
     */
    private function createGamePlay()
    {
        $this->game_play = GamePlay::create([
            'initiator_id' => $this->game_initiator->id,
            'game_version_id' => $this->game_version->id,
            'time_played' => $this->current_day->addSeconds(mt_rand(0, 24 * 60 * 60 - 1))
        ]);
    }

    /**
     * Sync players to game play
     * 
     * @return void
     */
    private function syncPlayersToGamePlay()
    {
        $this->game_play->players()
            ->sync(
                $this->getRandoGamePlayers()
                    ->pluck('id')->flip()->map(function ($value) {
                        return [
                            'score' => mt_rand(1, 100000)
                        ];
                    })
            );
    }

    /**
     * Get random game players
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    private function getRandoGamePlayers(): Collection
    {
        return Player::inRandomOrder()
            ->whereHas('gameVersions', function ($q) {
                $q->where('game_versions.id', $this->game_version->id);
            })->where('id', '!=', $this->game_initiator->id)
            ->limit(
                mt_rand(0, 3)
            )->get()
            ->push($this->game_initiator);
    }
}
