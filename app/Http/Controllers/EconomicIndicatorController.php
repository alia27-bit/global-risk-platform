<?php

namespace App\Http\Controllers;

use App\Models\Country;

class EconomicIndicatorController extends Controller
{
    public function show(Country $country)
    {
        $indicator = $country->economicIndicator;

        return view(
            'economy.show',
            compact(
                'country',
                'indicator'
            )
        );
    }

    public function sync(Country $country)
    {
        return back()->with(
            'success',
            'Economic indicator synchronized.'
        );
    }
}