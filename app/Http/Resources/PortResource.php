<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'country_id' => $this->negara_id,
            'name' => $this->nama_pelabuhan,
            'city' => $this->kota,
            'latitude' => (float) $this->lintang,
            'longitude' => (float) $this->bujur,
            'operational_status' => $this->status_operasional,
        ];
    }
}
