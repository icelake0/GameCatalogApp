<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'score' => $this->score
        ];
    }
}
