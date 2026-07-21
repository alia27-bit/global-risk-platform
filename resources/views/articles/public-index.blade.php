@extends('layouts.app')

@section('title', 'Artikel Analisis')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3>Artikel Analisis</h3>
        <p class="text-muted mb-0">Baca analisis risiko dan insight rantai pasok global.</p>
    </div>

    <div class="row g-4">
        @forelse ($articles as $article)
            <div class="col-md-6 col-xl-4">
                <article class="info-card h-100">
                    <div class="card-body d-flex flex-column">
                        <span class="badge text-bg-success align-self-start">Published</span>
                        <h5 class="mt-3">{{ $article->title }}</h5>
                        <p class="text-muted small mb-3">
                            {{ $article->user?->name ?? 'Admin' }} · {{ $article->published_at?->format('d M Y') }}
                        </p>
                        <p class="flex-grow-1">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 160) }}</p>
                        <a href="{{ route('articles.public.show', $article) }}" class="btn btn-outline-primary mt-2 align-self-start">Baca artikel</a>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="info-card">
                    <div class="card-body text-center py-5 text-muted">Belum ada artikel yang dipublikasikan.</div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $articles->links() }}</div>
</div>
@endsection
