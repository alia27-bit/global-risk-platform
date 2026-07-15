<?php

namespace App\Http\Controllers;

use App\Models\Country;

class ExchangeRateController extends Controller
{
    public function show(Country $country)
    {
        $exchange = $country->exchangeRate;

        return view(
            'exchange.show',
            compact(
                'country',
                'exchange'
            )
        );
    }

    public function sync(Country $country)
    {
        return back()->with(
            'success',
            'Exchange rate synchronized.'
        );
    }
}