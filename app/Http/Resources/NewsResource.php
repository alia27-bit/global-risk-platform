<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'country_id' => $this->negara_id,
            'country' => new CountryResource($this->whenLoaded('negara')),
            'title' => $this->judul,
            'description' => $this->deskripsi,
            'image_url' => $this->gambar,
            'source' => $this->sumber,
            'url' => $this->url,
            'published_at' => $this->published_at?->toIso8601String(),
            'sentiment' => $this->sentiment,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
