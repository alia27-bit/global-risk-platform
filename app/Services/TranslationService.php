<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TranslationService
{
    /**
     * Translate text menggunakan MyMemory API
     */
    public static function translate($text, $target = 'en')
    {
        if (empty($text)) {
            return '';
        }

        $cacheKey = 'translate_' . md5($text . $target);

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($text, $target) {

            $response = Http::get(
                'https://api.mymemory.translated.net/get',
                [
                    'q' => $text,
                    'langpair' => 'en|' . $target
                ]
            );

            if ($response->successful()) {

                return $response['responseData']['translatedText'];

            }

            return $text;

        });

    }
}