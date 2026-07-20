<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'capital' => $this->capital,
            'region' => $this->region,
            'subregion' => $this->subregion,
            'currency' => $this->currency,
            'currency_code' => $this->currency_code,
            'languages' => $this->languages ?? [],
            'population' => $this->population,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'flag_url' => $this->flag,
            'risk_score' => new RiskScoreResource($this->whenLoaded('riskScore')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
