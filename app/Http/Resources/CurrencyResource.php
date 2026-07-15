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
            'country_id' => $this->negara_id,
            'currency_code' => $this->mata_uang,
            'exchange_rate_to_usd' => (float) $this->kurs,
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
