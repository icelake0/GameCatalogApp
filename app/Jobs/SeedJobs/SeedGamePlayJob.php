<?php

namespace App\Jobs\SeedJobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SeedGamePlayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * First day of gaming
     * 
     * @var Carbon\Carbon
     */
    private $first_day;

    /**
     * Current day of gaming
     * 
     * @var Carbon\Carbon
     */
    private $current_day;

    /**
     * Create a new job instance.
     *
     * @param int $total number of Players to seed
     * @return void
     */
    public function __construct(Carbon $current_day = null)
    {
        $this->onQueue('daily-game-play');
        set_time_limit(0);
        $this->first_day = Carbon::parse('2010-01-01 00:00:00');
        $this->current_day = $current_day ?? $this->first_day;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->seedGamePlayForCurrentDay();
        $this->seedForMoreDaysIfCurrentDayIsNotLastday();
    }

    /**
     * Seed gameplay for current day
     * 
     * @return void
     */
    private function seedGamePlayForCurrentDay()
    {
        $total_game_plays_for_current_day = rand(1500, 1510);
        for ($i = 0; $i < $total_game_plays_for_current_day; $i++) {
            SeedGamePlayForDayJob::dispatch($this->current_day);
        }
    }

    /**
     * Seed game play for more days if current days is not last day
     * 
     * @return void
     */
    private function seedForMoreDaysIfCurrentDayIsNotLastday()
    {
        if ($this->currentDayIsNotLastDay()) {
            static::dispatch($this->current_day->addDay());
        }
    }

    /**
     * Check that current day is not last day of gaming
     * //TODO check that this condition works
     * 
     * @return bool
     */
    private function currentDayIsNotLastDay(): bool
    {
        return $this->first_day->diffInDays($this->current_day) < config('app.total_days_of_gameplay');
    }
}
