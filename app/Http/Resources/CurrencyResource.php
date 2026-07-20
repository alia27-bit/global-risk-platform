<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'country_id' => $this->country_id,
            'base_currency' => $this->base_currency,
            'target_currency' => $this->target_currency,
            'exchange_rate' => (float) $this->exchange_rate,
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
