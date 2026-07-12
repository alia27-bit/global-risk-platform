<?php

namespace App\Http\Controllers;

use App\Models\Negara;

class ComparisonController extends Controller
{
    public function index()
    {
        $countries = Negara::orderBy('nama_negara')->get();

        return view('comparison.index', compact('countries'));
    }

    public function compare()
    {
        $left = Negara::findOrFail(request('left'));
        $right = Negara::findOrFail(request('right'));

        return view('comparison.result', compact(
            'left',
            'right'
        ));
    }
}