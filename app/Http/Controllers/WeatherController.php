<?php

namespace App\Http\Controllers;

use App\Models\Country;

class WeatherController extends Controller
{
    public function show(Country $country)
    {
        $weather = $country->weather;

        return view('weather.show', compact(
            'country',
            'weather'
        ));
    }

    public function sync(Country $country)
    {
        return back()->with(
            'success',
            'Weather synchronized.'
        );
    }
}