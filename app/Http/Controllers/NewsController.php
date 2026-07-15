<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Country;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('country')
            ->latest()
            ->paginate(10);

        return view('news.index', compact('news'));
    }

    public function show(News $news)
    {
        return view('news.show', compact('news'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();

        return view('news.create', compact('countries'));
    }

    public function store(Request $request)
    {
        News::create($request->all());

        return redirect()
            ->route('news.index')
            ->with('success', 'News created successfully.');
    }

    public function edit(News $news)
    {
        $countries = Country::orderBy('name')->get();

        return view('news.edit', compact('news', 'countries'));
    }

    public function update(Request $request, News $news)
    {
        $news->update($request->all());

        return redirect()
            ->route('news.index')
            ->with('success', 'News updated successfully.');
    }

    public function destroy(News $news)
    {
        $news->delete();

        return redirect()
            ->route('news.index')
            ->with('success', 'News deleted.');
    }

    public function sync(Country $country)
    {
        return back()->with(
            'success',
            'News synchronized successfully.'
        );
    }
}