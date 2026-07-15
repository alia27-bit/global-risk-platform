<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name')->get();

        return view(
            'comparison.index',
            compact('countries')
        );
    }

    public function compare(Request $request)
    {
        $countryA = Country::findOrFail($request->country_a);
        $countryB = Country::findOrFail($request->country_b);

        return view(
            'comparison.result',
            compact(
                'countryA',
                'countryB'
            )
        );
    }
}