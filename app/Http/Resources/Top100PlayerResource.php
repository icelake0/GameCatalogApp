<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class Top100PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return PlayerResource::make($this->player)->toArray($request) + [
            'score' => $this->score,
            'game_play_link' => $this->getGamePlayLink()
        ];
    }

    /**
     * Get link to the game played by player in the month
     * 
     * @return string
     */
    private function getGamePlayLink(): string
    {
        $url =  route('api-v1-game-plays-index');
        try {
            $date = Carbon::parse($this->month);
        } catch (\Exception $e) {
            $date = Carbon::today();
        }
        $start_date = $date->startOfMonth()->format('Y-m-d');
        $end_date = $date->endOfMonth()->format('Y-m-d');
        $params = "date_played_range[0]={$start_date}&date_played_range[1]={$end_date}&initiator={$this->player->id}";
        $url = "{$url}?{$params}";
        return $url;
    }
}
