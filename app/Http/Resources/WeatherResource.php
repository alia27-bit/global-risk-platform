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
            'country_id' => $this->negara_id,
            'temperature' => (float) $this->temperatur,
            'rainfall' => (float) $this->curah_hujan,
            'wind_speed' => (float) $this->kecepatan_angin,
            'storm_risk' => (float) $this->risiko_badai,
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
