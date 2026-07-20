<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiskScoreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'country_id' => $this->country_id,
            'country' => new CountryResource($this->whenLoaded('country')),
            'weather_score' => (float) $this->weather_score,
            'economic_score' => (float) $this->economic_score,
            'currency_score' => (float) $this->currency_score,
            'news_score' => (float) $this->news_score,
            'total_score' => (float) $this->total_score,
            'category' => $this->category,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
