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
            'iso3_code' => $this->kode_iso3,
            'iso2_code' => $this->kode_iso2,
            'name' => $this->nama_negara,
            'capital' => $this->ibukota,
            'region' => $this->wilayah,
            'sub_region' => $this->sub_wilayah,
            'currency' => $this->mata_uang,
            'population' => $this->populasi,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'flag_url' => $this->bendera,
            'risk_score' => new RiskScoreResource($this->whenLoaded('skorRisiko', function () {
                return $this->skorRisiko->first();
            })),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
