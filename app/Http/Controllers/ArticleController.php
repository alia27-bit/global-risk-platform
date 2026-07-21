<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        return view('articles.index', [
            'articles' => Article::with('user')->latest()->paginate(15),
        ]);
    }

    public function publicIndex()
    {
        return view('articles.public-index', [
            'articles' => Article::query()
                ->where('status', 'Published')
                ->whereNotNull('published_at')
                ->latest('published_at')
                ->paginate(12),
        ]);
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['user_id'] = $request->user()->id;
        $data['slug'] = $this->slug($data['title']);

        if ($data['status'] === 'Published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        Article::create($data);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel dibuat.');
    }

    public function show(Article $article)
    {
        return view('articles.show', [
            'article' => $article->load('user'),
            'backRoute' => route('admin.articles.index'),
            'backLabel' => 'Kembali ke daftar admin',
        ]);
    }

    public function publicShow(Article $article)
    {
        abort_unless($article->status === 'Published', 404);

        return view('articles.show', [
            'article' => $article->load('user'),
            'backRoute' => route('articles.public.index'),
            'backLabel' => 'Kembali ke artikel',
        ]);
    }

    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $data = $this->validated($request);

        if ($data['title'] !== $article->title) {
            $data['slug'] = $this->slug($data['title'], $article);
        }

        if ($data['status'] === 'Published' && empty($data['published_at'])) {
            $data['published_at'] = $article->published_at ?? now();
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel diperbarui.');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return back()->with('success', 'Artikel dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'status' => ['required', 'in:Draft,Published'],
            'published_at' => ['nullable', 'date'],
        ]);
    }

    private function slug(string $title, ?Article $ignore = null): string
    {
        $base = Str::slug($title) ?: 'article';
        $slug = $base;
        $i = 2;

        while (Article::where('slug', $slug)->when($ignore, fn ($query) => $query->whereKeyNot($ignore->id))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
