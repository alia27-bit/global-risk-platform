<?php

namespace App\Http\Controllers;

use App\Models\Negara;
use App\Models\SkorRisiko;
use App\Services\RiskScoringService;

class RiskController extends Controller
{
    protected $risk;

    public function __construct(RiskScoringService $risk)
    {
        $this->risk = $risk;
    }

    public function calculate($id)
    {
        $country = Negara::with([
            'cuaca',
            'ekonomi',
            'kurs',
            'berita'
        ])->findOrFail($id);

        $weather = rand(20,80);

        $inflation = rand(10,70);

        $currency = rand(10,60);

        $news = rand(20,90);

        $score = $this->risk->calculate(
            $weather,
            $inflation,
            $currency,
            $news
        );

        $kategori = $this->risk->category($score);

        SkorRisiko::updateOrCreate(
            [
                'negara_id' => $country->id
            ],
            [
                'weather_score' => $weather,
                'inflation_score' => $inflation,
                'currency_score' => $currency,
                'news_score' => $news,
                'total_skor' => $score,
                'kategori' => $kategori
            ]
        );

        return back()->with('success', 'Risk Score berhasil dihitung.');
    }
}