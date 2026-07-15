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
            'country_id' => $this->negara_id,
            'country' => new CountryResource($this->whenLoaded('negara')),
            'weather_score' => (float) $this->weather_score,
            'inflation_score' => (float) $this->inflation_score,
            'currency_score' => (float) $this->currency_score,
            'news_score' => (float) $this->news_score,
            'total_score' => (float) $this->total_skor,
            'category' => $this->kategori,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
