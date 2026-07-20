<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeatherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'country_id' => $this->country_id,
            'country' => new CountryResource($this->whenLoaded('country')),
            'temperature' => (float) $this->temperature,
            'rainfall' => (float) $this->rainfall,
            'wind_speed' => (float) $this->wind_speed,
            'weather_code' => $this->weather_code,
            'storm_risk' => (float) $this->storm_risk,
            'observed_at' => $this->observed_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
