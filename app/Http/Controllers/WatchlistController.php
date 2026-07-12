<?php

namespace App\Http\Controllers;

use App\Models\DaftarPantauan;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        $watchlists = DaftarPantauan::with('negara')
            ->where('user_id', Auth::id())
            ->get();

        return view('watchlist.index', compact('watchlists'));
    }

    public function store($countryId)
    {
        DaftarPantauan::firstOrCreate(

            [
                'user_id' => Auth::id(),
                'negara_id' => $countryId
            ]

        );

        return back()->with('success', 'Negara ditambahkan.');
    }

    public function destroy($id)
    {
        DaftarPantauan::findOrFail($id)->delete();

        return back()->with('success', 'Berhasil dihapus.');
    }
}