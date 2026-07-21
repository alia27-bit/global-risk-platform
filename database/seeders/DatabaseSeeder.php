<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\SentimentDictionary;
use App\Models\PositiveWord;
use App\Models\NegativeWord;
use App\Models\Country;
use App\Models\Port;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@example.com'], [
            'nama'     => 'Administrator',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password'),
            'peran'    => 'admin',
        ]);

        User::updateOrCreate(['email' => 'user@example.com'], [
            'nama'     => 'User Biasa',
            'email'    => 'user@example.com',
            'password' => Hash::make('password'),
            'peran'    => 'user',
        ]);

        // Seed sentiment_dictionaries (legacy)
        foreach (['growth', 'increase', 'profit', 'stable', 'improve', 'recovery', 'strong', 'gain'] as $word) {
            SentimentDictionary::updateOrCreate(['word' => $word], ['type' => 'positive']);
        }

        foreach (['war', 'crisis', 'inflation', 'delay', 'disaster', 'decrease', 'conflict', 'shortage', 'disruption'] as $word) {
            SentimentDictionary::updateOrCreate(['word' => $word], ['type' => 'negative']);
        }

        // Seed positive_words (digunakan oleh SentimentService)
        foreach ([
            'growth', 'grow', 'increase', 'profit', 'stable', 'improve',
            'recovery', 'strong', 'gain', 'surge', 'benefit', 'optimistic',
            'resilient', 'boost', 'thrive', 'prosper', 'advance', 'expand',
            'efficient', 'reliable', 'secure', 'innovative', 'sustainable',
        ] as $word) {
            PositiveWord::updateOrCreate(['word' => $word]);
        }

        // Seed negative_words (digunakan oleh SentimentService)
        foreach ([
            'war', 'crisis', 'inflation', 'delay', 'disaster', 'decrease',
            'conflict', 'shortage', 'disruption', 'decline', 'loss', 'weak',
            'risk', 'storm', 'recession', 'collapse', 'threat', 'sanction',
            'embargo', 'unstable', 'volatile', 'corruption', 'bankrupt',
        ] as $word) {
            NegativeWord::updateOrCreate(['word' => $word]);
        }

        foreach ([
            ['IDN', 'Port of Tanjung Priok', -6.104, 106.886],
            ['SGP', 'Port of Singapore', 1.264, 103.840],
            ['CHN', 'Port of Shanghai', 31.230, 121.500],
            ['DEU', 'Port of Hamburg', 53.546, 9.966],
            ['AUS', 'Port of Melbourne', -37.841, 144.917],
            ['USA', 'Port of Los Angeles', 33.740, -118.265],
        ] as [$code, $name, $latitude, $longitude]) {
            $country = Country::where('code', $code)->first();
            if ($country) Port::firstOrCreate(['country_id' => $country->id, 'name' => $name], compact('latitude', 'longitude'));
        }
    }
}

