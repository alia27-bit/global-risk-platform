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
            'country_id' => $this->negara_id,
            'gdp' => $this->gdp ? (float) $this->gdp : null,
            'inflation' => $this->inflasi ? (float) $this->inflasi : null,
            'population' => $this->populasi,
            'exports' => $this->ekspor ? (float) $this->ekspor : null,
            'imports' => $this->impor ? (float) $this->impor : null,
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
