<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Services\Api\NegaraService;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::latest()->paginate(20);

        return view('countries.index', compact('countries'));
    }

    public function create()
    {
        return view('countries.create');
    }

    public function store(Request $request)
    {
        Country::create($this->validated($request));

        return redirect()
            ->route('countries.index')
            ->with('success','Country created successfully.');
    }

    public function show(Country $country)
    {
        $country->load(['weather', 'economicIndicator', 'exchangeRate', 'riskScore', 'ports', 'news.sentimentAnalysis']);
        return view('countries.show', compact('country'));
    }

    public function edit(Country $country)
    {
        return view('countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $country->update($this->validated($request, $country));

        return redirect()
            ->route('countries.index')
            ->with('success','Country updated.');
    }

    public function destroy(Country $country)
    {
        $country->delete();

        return redirect()
            ->route('countries.index')
            ->with('success','Country deleted.');
    }

    public function sync(NegaraService $service)
    {
        $synchronized = $service->sync();

        return back()->with(
            $synchronized ? 'success' : 'error',
            $synchronized
                ? "{$synchronized} country records synchronized successfully."
                : 'Country synchronization failed because the external API returned no valid country data.'
        );
    }

    private function validated(Request $request, ?Country $country = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:5', 'unique:countries,code'.($country ? ','.$country->id : '')],
            'alpha2' => ['nullable', 'string', 'size:2'],
            'capital' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:100'],
            'subregion' => ['nullable', 'string', 'max:100'],
            'population' => ['nullable', 'integer', 'min:0'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'currency' => ['nullable', 'string', 'max:100'],
            'currency_code' => ['nullable', 'string', 'max:10'],
            'flag' => ['nullable', 'url', 'max:2048'],
        ]);
    }
}
