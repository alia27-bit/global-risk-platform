<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Services\Api\WorldPortService;
use Throwable;

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
        Port::create($this->validated($request));

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

    public function sync(WorldPortService $service)
    {
        try {
            $count = $service->sync();
            return back()->with('success', number_format($count).' pelabuhan World Port Index berhasil disinkronkan.');
        } catch (Throwable $exception) {
            return back()->with('error', 'Sinkronisasi World Port Index gagal: '.$exception->getMessage());
        }
    }

    public function show(Port $port)
    {
        return view('ports.show', ['port' => $port->load('country')]);
    }

    public function update(Request $request, Port $port)
    {
        $port->update($this->validated($request));

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
        $portMarkers = $ports->map(function (Port $port) {
            return [
                'name' => $port->name,
                'country' => $port->country?->name,
                'lat' => (float) $port->latitude,
                'lng' => (float) $port->longitude,
                'url' => route('ports.show', $port),
            ];
        });

        return view('ports.map', compact('ports', 'portMarkers'));
    }

    public function search(Request $request)
    {
        $keyword = $request->validate(['keyword' => ['required', 'string', 'max:100']])['keyword'];
        $ports = Port::with('country')->where(function ($query) use ($keyword) {
            $query->where('name', 'like', '%'.$keyword.'%')
                ->orWhereHas('country', fn ($country) => $country->where('name', 'like', '%'.$keyword.'%'));
        })->orderBy('name')->limit(50)->get()->map(fn (Port $port) => [
            'name' => $port->name,
            'country' => $port->country ? ['name' => $port->country->name] : null,
            'latitude' => $port->latitude,
            'longitude' => $port->longitude,
            'url' => route('ports.show', $port),
        ]);

        return response()->json($ports);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'country_id' => ['required', 'exists:countries,id'],
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);
    }
}
