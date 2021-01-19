<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameWithVersionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return GameResource::make($this->resource)->toArray($request) + [
            'versions' => VersionResource::collection($this->versions)
        ];
    }
}
