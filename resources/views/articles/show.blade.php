@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="container-fluid">
    <a href="{{ $backRoute }}" class="btn btn-outline-secondary mb-3">{{ $backLabel }}</a>
    <article class="info-card">
        <div class="card-body p-5">
            <span class="badge text-bg-{{ $article->status === 'Published' ? 'success' : 'secondary' }}">{{ $article->status }}</span>
            <h2 class="mt-3">{{ $article->title }}</h2>
            <p class="text-muted">{{ $article->user?->name }} · {{ $article->published_at?->format('d M Y H:i') ?? 'Belum dipublikasikan' }}</p>
            <hr>
            <div style="white-space: pre-line; line-height: 1.8">{{ $article->content }}</div>
        </div>
    </article>
</div>
@endsection
