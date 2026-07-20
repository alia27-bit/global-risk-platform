<?php

namespace App\Services;

use App\Models\SentimentDictionary;

class SentimentService
{
    private const POSITIVE_FALLBACK = ['growth','grow','increase','profit','stable','improve','recovery','strong','gain','surge','benefit','optimistic','resilient'];
    private const NEGATIVE_FALLBACK = ['war','crisis','inflation','delay','disaster','decrease','conflict','shortage','disruption','decline','loss','weak','risk','storm'];

    public function analyze(?string $text): array
    {
        $dictionary = SentimentDictionary::query()->get(['word', 'type'])->groupBy('type');
        $positive = $this->lexicon($dictionary->get('positive')?->pluck('word')->all() ?? [], self::POSITIVE_FALLBACK);
        $negative = $this->lexicon($dictionary->get('negative')?->pluck('word')->all() ?? [], self::NEGATIVE_FALLBACK);
        $words = preg_split('/[^\pL]+/u', mb_strtolower(strip_tags((string) $text)), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $positiveScore = 0; $negativeScore = 0; $matches = [];

        foreach ($words as $word) {
            $forms = $this->forms($word);
            if (array_intersect($forms, $positive)) { $positiveScore++; $matches[] = ['word' => $word, 'type' => 'positive']; }
            if (array_intersect($forms, $negative)) { $negativeScore++; $matches[] = ['word' => $word, 'type' => 'negative']; }
        }

        $label = $positiveScore > $negativeScore ? 'Positive' : ($negativeScore > $positiveScore ? 'Negative' : 'Neutral');
        $matched = $positiveScore + $negativeScore;
        return [
            'label' => $label, 'positive' => $positiveScore, 'negative' => $negativeScore,
            'neutral' => $label === 'Neutral' ? 1 : 0,
            'positive_percentage' => $matched ? round($positiveScore / $matched * 100, 1) : 0.0,
            'negative_percentage' => $matched ? round($negativeScore / $matched * 100, 1) : 0.0,
            'neutral_percentage' => $matched ? 0.0 : 100.0, 'matches' => $matches,
        ];
    }

    private function lexicon(array $stored, array $fallback): array
    {
        return array_values(array_unique(array_map(fn ($word) => mb_strtolower(trim($word)), array_merge($stored, $fallback))));
    }

    private function forms(string $word): array
    {
        $forms = [$word];
        foreach (['ing', 'ed', 'es', 's'] as $suffix) {
            if (mb_strlen($word) > mb_strlen($suffix) + 2 && str_ends_with($word, $suffix)) $forms[] = mb_substr($word, 0, -mb_strlen($suffix));
        }
        return array_unique($forms);
    }
}
