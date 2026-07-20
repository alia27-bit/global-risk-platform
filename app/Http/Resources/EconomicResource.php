<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EconomicResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'country_id' => $this->country_id,
            'gdp' => $this->gdp ? (float) $this->gdp : null,
            'inflation' => $this->inflation !== null ? (float) $this->inflation : null,
            'unemployment' => $this->unemployment !== null ? (float) $this->unemployment : null,
            'exports' => $this->exports !== null ? (float) $this->exports : null,
            'imports' => $this->imports !== null ? (float) $this->imports : null,
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
