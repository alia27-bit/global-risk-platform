<?php

namespace App\Http\Controllers;

use App\Models\Pelabuhan;

class PortController extends Controller
{
    public function index()
    {
        $ports = Pelabuhan::paginate(25);

        return view('ports.index', compact('ports'));
    }

    public function map()
    {
        $ports = Pelabuhan::all();

        return view('ports.map', compact('ports'));
    }

    public function search()
    {
        $keyword = request('keyword');

        $ports = Pelabuhan::where('nama_pelabuhan', 'like', "%{$keyword}%")
            ->orWhere('negara', 'like', "%{$keyword}%")
            ->paginate(25);

        return view('ports.index', compact('ports'));
    }
}