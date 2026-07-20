<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Services\MonitoringSyncService;
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

    public function compare(Request $request, MonitoringSyncService $sync)
    {
        $validated = $request->validate([
            'country_a' => ['required', 'exists:countries,id', 'different:country_b'],
            'country_b' => ['required', 'exists:countries,id'],
        ]);

        $relations = ['weather', 'economicIndicator', 'exchangeRate', 'riskScore'];
        $countries = Country::with($relations)
            ->whereIn('id', [$validated['country_a'], $validated['country_b']])
            ->get()
            ->keyBy('id');

        foreach ($countries as $country) {
            if (! $country->weather) {
                $sync->weather($country);
            }

            if (! $country->economicIndicator) {
                $sync->economy($country);
            }

            if (! $country->exchangeRate) {
                $sync->exchange($country);
            }

            $country->refresh()->load($relations);

            if (! $country->riskScore && ($country->weather || $country->economicIndicator || $country->exchangeRate)) {
                $sync->risk($country);
            }
        }

        $countryA = Country::with($relations)->findOrFail($validated['country_a']);
        $countryB = Country::with($relations)->findOrFail($validated['country_b']);

        return view(
            'comparison.result',
            compact(
                'countryA',
                'countryB'
            )
        );
    }
}
