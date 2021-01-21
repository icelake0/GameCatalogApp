<?php

namespace App\Jobs\SeedJobs;

use App\Models\Player;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StartAttemptingGamePlaySeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('daily-game-play');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (Player::count() > config('app.min_plays_per_day')) {
            SeedGamePlayJob::dispatch();
        } else {
            static::dispatch()->delay(now()->addMinutes(1));
        }
    }
}
