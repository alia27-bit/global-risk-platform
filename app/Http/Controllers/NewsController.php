<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Negara;
use App\Services\Api\GNewsService;
use App\Services\SentimentService;

class NewsController extends Controller
{
    protected $newsService;
    protected $sentimentService;

    public function __construct(
        GNewsService $newsService,
        SentimentService $sentimentService
    ) {
        $this->newsService = $newsService;
        $this->sentimentService = $sentimentService;
    }

    public function index()
    {
        $news = Berita::with('negara')
            ->latest()
            ->paginate(20);

        return view('news.index', compact('news'));
    }

    public function sync($countryId)
    {
        $country = Negara::findOrFail($countryId);

        $articles = $this->newsService->search($country->nama_negara);

        foreach ($articles as $article) {

            $sentiment = $this->sentimentService
                ->analyze($article['title'] . ' ' . $article['description']);

            Berita::updateOrCreate(

                [
                    'url' => $article['url']
                ],

                [
                    'negara_id' => $country->id,
                    'judul' => $article['title'],
                    'deskripsi' => $article['description'],
                    'gambar' => $article['image'] ?? null,
                    'sumber' => $article['source']['name'] ?? '',
                    'published_at' => $article['publishedAt'],
                    'sentiment' => $sentiment['label']
                ]
            );
        }

        return redirect()->back()
            ->with('success', 'Berita berhasil diperbarui.');
    }
}