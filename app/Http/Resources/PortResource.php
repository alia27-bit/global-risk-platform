<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'country_id' => $this->country_id,
            'country' => new CountryResource($this->whenLoaded('country')),
            'name' => $this->name,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
        ];
    }
}
