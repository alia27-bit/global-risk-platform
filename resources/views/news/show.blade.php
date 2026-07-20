@extends('layouts.app')
@section('title', $news->title)
@section('content')
<div class="container-fluid"><a href="{{ route('news.index') }}" class="btn btn-outline-secondary btn-sm mb-3"><i class="bi bi-arrow-left"></i> Kembali</a><article class="info-card"><div class="card-body p-4 p-lg-5">
    @php($sentimentTotal = max(1, ($news->sentimentAnalysis?->positive ?? 0) + ($news->sentimentAnalysis?->negative ?? 0) + ($news->sentimentAnalysis?->neutral ?? 0)))
    <div class="d-flex gap-2 mb-3"><span class="badge text-bg-light">{{ $news->country?->name ?? 'Global' }}</span><span class="badge text-bg-secondary">{{ $news->sentimentAnalysis?->result ?? 'Neutral' }}</span></div>
    <div class="row g-2 mb-4"><div class="col-sm-4"><div class="alert alert-success mb-0 py-2">Positive: {{ round((($news->sentimentAnalysis?->positive ?? 0) / $sentimentTotal) * 100) }}%</div></div><div class="col-sm-4"><div class="alert alert-secondary mb-0 py-2">Neutral: {{ round((($news->sentimentAnalysis?->neutral ?? 0) / $sentimentTotal) * 100) }}%</div></div><div class="col-sm-4"><div class="alert alert-danger mb-0 py-2">Negative: {{ round((($news->sentimentAnalysis?->negative ?? 0) / $sentimentTotal) * 100) }}%</div></div></div>
    <h2>{{ $news->title }}</h2><p class="text-muted">{{ $news->source ?: 'Unknown source' }} · {{ $news->published_at?->format('d M Y H:i') ?? '-' }}</p><hr><div style="white-space:pre-line;line-height:1.8">{{ $news->content ?: 'Konten tidak tersedia.' }}</div>
    @if($news->url)<a href="{{ $news->url }}" target="_blank" rel="noopener" class="btn btn-primary mt-4">Buka sumber <i class="bi bi-box-arrow-up-right ms-1"></i></a>@endif
</div></article></div>
@endsection
