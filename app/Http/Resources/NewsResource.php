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
            'country_id' => $this->country_id,
            'country' => new CountryResource($this->whenLoaded('country')),
            'title' => $this->title,
            'content' => $this->content,
            'source' => $this->source,
            'url' => $this->url,
            'published_at' => $this->published_at?->toIso8601String(),
            'sentiment' => $this->whenLoaded('sentimentAnalysis', fn () => $this->sentimentAnalysis?->result),
            'sentiment_scores' => $this->whenLoaded('sentimentAnalysis', fn () => [
                'positive' => (int) ($this->sentimentAnalysis?->positive ?? 0),
                'neutral' => (int) ($this->sentimentAnalysis?->neutral ?? 0),
                'negative' => (int) ($this->sentimentAnalysis?->negative ?? 0),
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
