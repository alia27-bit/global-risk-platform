<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Country;
use Illuminate\Http\Request;

class PortController extends Controller
{
    public function index()
    {
        $ports = Port::with('country')
            ->paginate(20);

        return view('ports.index', compact('ports'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();

        return view('ports.create', compact('countries'));
    }

    public function store(Request $request)
    {
        Port::create($request->all());

        return redirect()
            ->route('ports.index')
            ->with('success', 'Port created.');
    }

    public function edit(Port $port)
    {
        $countries = Country::orderBy('name')->get();

        return view('ports.edit', compact(
            'port',
            'countries'
        ));
    }

    public function update(Request $request, Port $port)
    {
        $port->update($request->all());

        return redirect()
            ->route('ports.index')
            ->with('success', 'Port updated.');
    }

    public function destroy(Port $port)
    {
        $port->delete();

        return redirect()
            ->route('ports.index')
            ->with('success', 'Port deleted.');
    }

    public function map()
    {
        $ports = Port::with('country')->get();

        return view('ports.map', compact('ports'));
    }

    public function search(Request $request)
    {
        $ports = Port::where(
            'name',
            'like',
            '%' . $request->keyword . '%'
        )->get();

        return response()->json($ports);
    }
}