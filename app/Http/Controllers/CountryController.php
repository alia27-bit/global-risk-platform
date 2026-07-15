<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

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
        Country::create($request->all());

        return redirect()
            ->route('countries.index')
            ->with('success','Country created successfully.');
    }

    public function show(Country $country)
    {
        return view('countries.show', compact('country'));
    }

    public function edit(Country $country)
    {
        return view('countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $country->update($request->all());

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

    public function sync()
    {
        return back()->with('success','Country synchronized.');
    }
}