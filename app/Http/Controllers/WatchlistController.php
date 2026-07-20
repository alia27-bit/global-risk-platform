<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        $watchlists = Watchlist::with('country.riskScore')
            ->where('user_id', Auth::id())
            ->get();

        $countries = Country::query()
            ->whereNotIn('id', $watchlists->pluck('country_id'))
            ->orderBy('name')
            ->get();

        return view(
            'watchlist.index',
            compact('watchlists', 'countries')
        );
    }

    public function store(Country $country)
    {
        Watchlist::firstOrCreate([
            'user_id' => Auth::id(),
            'country_id' => $country->id,
        ]);

        return back()->with(
            'success',
            'Country added to watchlist.'
        );
    }

    public function destroy(Watchlist $watchlist)
    {
        abort_unless($watchlist->user_id === Auth::id(), 403);

        $watchlist->delete();

        return back()->with(
            'success',
            'Country removed from watchlist.'
        );
    }
}
