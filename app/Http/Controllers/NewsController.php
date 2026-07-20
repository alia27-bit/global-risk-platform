<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\ApiLog;
use App\Models\SentimentAnalysis;
use App\Services\Api\GNewsService;
use App\Services\SentimentService;
use Carbon\Carbon;
use Throwable;

class NewsController extends Controller
{
    public function index(GNewsService $service)
    {
        $news = News::with(['country', 'sentimentAnalysis'])->latest('published_at')->paginate(10);
        $sentimentSummary = SentimentAnalysis::query()
            ->selectRaw('result, COUNT(*) as total')->groupBy('result')->pluck('total', 'result');
        $sentimentTotal = max(1, $sentimentSummary->sum());

        return view('news.index', compact('news', 'sentimentSummary', 'sentimentTotal') + ['gnewsConfigured' => $service->configured()]);
    }

    public function show(News $news)
    {
        $news->load(['country', 'sentimentAnalysis']);

        return view('news.show', compact('news'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();

        return view('news.create', compact('countries'));
    }

    public function store(Request $request, SentimentService $sentiment)
    {
        $news = News::create($this->validated($request));
        $this->analyze($news, $sentiment);

        return redirect()
            ->route('news.index')
            ->with('success', 'News created successfully.');
    }

    public function edit(News $news)
    {
        $countries = Country::orderBy('name')->get();

        return view('news.edit', compact('news', 'countries'));
    }

    public function update(Request $request, News $news, SentimentService $sentiment)
    {
        $news->update($this->validated($request));
        $this->analyze($news, $sentiment);

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

    public function sync(Country $country, GNewsService $service, SentimentService $sentiment)
    {
        if (! $service->configured()) {
            return back()->with('error', 'Isi GNEWS_API_KEY yang valid di file .env untuk menggunakan GNews.');
        }

        try {
            $articles = $service->search($country->name);
            $saved = 0;
            foreach ($articles as $article) {
                if (! is_array($article) || empty($article['title']) || empty($article['url'])) continue;
                $news = News::updateOrCreate(['url' => $article['url']], [
                    'country_id' => $country->id,
                    'title' => $article['title'],
                    'content' => $article['content'] ?? $article['description'] ?? null,
                    'source' => data_get($article, 'source.name'),
                    'published_at' => ! empty($article['publishedAt']) ? Carbon::parse($article['publishedAt']) : now(),
                ]);
                $this->analyze($news, $sentiment);
                $saved++;
            }
            ApiLog::create(['api_name' => 'GNews', 'status_code' => $saved ? 200 : 204, 'message' => "{$saved} articles synchronized for {$country->code}"]);
            return back()->with($saved ? 'success' : 'error', $saved ? "{$saved} berita berhasil disinkronkan dan dianalisis." : 'GNews tidak mengembalikan artikel baru.');
        } catch (Throwable $exception) {
            ApiLog::create(['api_name' => 'GNews', 'status_code' => 500, 'message' => 'Sinkronisasi negara gagal: '.$exception->getMessage()]);
            return back()->with('error', 'Koneksi GNews gagal. Pastikan akun sudah diaktivasi melalui email, lalu periksa API key dan kuota.');
        }
    }

    public function syncGlobal(GNewsService $service, SentimentService $sentiment)
    {
        if (! $service->configured()) {
            return back()->with('error', 'GNews belum aktif. Tempel API key dari akun GNews ke GNEWS_API_KEY di file .env.');
        }

        try {
            $saved = $this->saveArticles($service->globalSupplyChain(), null, $sentiment);
            ApiLog::create(['api_name' => 'GNews', 'status_code' => $saved ? 200 : 204, 'message' => "{$saved} global articles synchronized"]);
            return back()->with($saved ? 'success' : 'error', $saved ? "{$saved} berita global berhasil disinkronkan dan dianalisis." : 'GNews tidak mengembalikan artikel baru.');
        } catch (Throwable $exception) {
            ApiLog::create(['api_name' => 'GNews', 'status_code' => 500, 'message' => 'Sinkronisasi global gagal: '.$exception->getMessage()]);
            return back()->with('error', 'Koneksi GNews gagal. Pastikan akun sudah diaktivasi melalui email, lalu periksa API key dan kuota.');
        }
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'country_id' => ['nullable', 'exists:countries,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'source' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:2048'],
            'published_at' => ['nullable', 'date'],
        ]);
    }

    private function analyze(News $news, SentimentService $service): void
    {
        $result = $service->analyze($news->title.' '.$news->content);
        SentimentAnalysis::updateOrCreate(['news_id' => $news->id], [
            'positive' => $result['positive'],
            'negative' => $result['negative'],
            'neutral' => $result['neutral'],
            'result' => $result['label'],
        ]);
    }

    private function saveArticles(array $articles, ?Country $country, SentimentService $sentiment): int
    {
        $saved = 0;
        foreach ($articles as $article) {
            if (! is_array($article) || empty($article['title']) || empty($article['url'])) continue;
            $news = News::updateOrCreate(['url' => $article['url']], [
                'country_id' => $country?->id,
                'title' => $article['title'],
                'content' => $article['content'] ?? $article['description'] ?? null,
                'source' => data_get($article, 'source.name'),
                'published_at' => ! empty($article['publishedAt']) ? Carbon::parse($article['publishedAt']) : now(),
            ]);
            $this->analyze($news, $sentiment);
            $saved++;
        }
        return $saved;
    }
}
