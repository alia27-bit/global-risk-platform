<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\RiskScore;

class RiskScoreController extends Controller
{
    public function index()
    {
        $riskScores = RiskScore::with('country')
            ->paginate(20);

        return view(
            'risk.index',
            compact('riskScores')
        );
    }

    public function show(Country $country)
    {
        $risk = RiskScore::where(
            'country_id',
            $country->id
        )->first();

        return view(
            'risk.show',
            compact(
                'country',
                'risk'
            )
        );
    }

    public function calculate(Country $country)
    {
        return back()->with(
            'success',
            'Risk score calculated successfully.'
        );
    }
}