@extends('layouts.app')
@section('title', 'News Intelligence')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4"><div><h3 class="mb-1">News Intelligence</h3><p class="text-muted mb-0">Berita ekonomi, logistik, perdagangan, dan geopolitik.</p></div>@if(auth()->user()->peran === 'admin')<div class="d-flex gap-2"><a href="{{ route('news.sync-global') }}" class="btn btn-success"><i class="bi bi-cloud-download me-1"></i>Sinkronkan GNews</a><a href="{{ route('news.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Berita</a></div>@endif</div>
    @if(auth()->user()->peran === 'admin' && ! $gnewsConfigured)<div class="alert alert-warning"><i class="bi bi-key me-2"></i>GNews belum aktif. Isi <code>GNEWS_API_KEY</code> di file <code>.env</code>, jalankan <code>php artisan optimize:clear</code>, lalu klik Sinkronkan GNews.</div>@endif
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    <div class="row g-3 mb-4">
        @foreach([['Positive','success'],['Neutral','secondary'],['Negative','danger']] as [$label,$color])
        @php($count = (int) ($sentimentSummary[$label] ?? 0))
        <div class="col-md-4"><div class="stat-card h-100"><div class="card-body"><div class="d-flex justify-content-between"><span class="stat-label">{{ $label }}</span><strong>{{ round($count / $sentimentTotal * 100, 1) }}%</strong></div><div class="progress mt-3" style="height:8px"><div class="progress-bar bg-{{ $color }}" style="width:{{ $count / $sentimentTotal * 100 }}%"></div></div><small class="text-muted">{{ $count }} artikel</small></div></div></div>
        @endforeach
    </div>
    <div class="row g-4">
        @forelse($news as $article)
        @php($sentiment = $article->sentimentAnalysis?->result ?? 'Neutral')
        @php($badge = $sentiment === 'Positive' ? 'success' : ($sentiment === 'Negative' ? 'danger' : 'secondary'))
        <div class="col-md-6 col-xl-4"><article class="info-card h-100"><div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between mb-3"><span class="badge text-bg-light">{{ $article->country?->name ?? 'Global' }}</span><span class="badge text-bg-{{ $badge }}">{{ $sentiment }}</span></div>
            <h5>{{ $article->title }}</h5><p class="text-muted small flex-grow-1">{{ str(strip_tags($article->content))->limit(150) ?: 'Tidak ada ringkasan.' }}</p>
            <div class="small text-muted mb-3"><i class="bi bi-building me-1"></i>{{ $article->source ?: 'Unknown source' }} &middot; {{ $article->published_at?->diffForHumans() ?? '-' }}</div>
            <div class="d-flex gap-2"><a href="{{ route('news.show', $article) }}" class="btn btn-outline-primary btn-sm">Baca detail</a>@if(auth()->user()->peran === 'admin')<a href="{{ route('news.edit', $article) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-pencil"></i></a><form method="POST" action="{{ route('news.destroy', $article) }}" onsubmit="return confirm('Hapus berita ini?')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button></form>@endif</div>
        </div></article></div>
        @empty<div class="col-12"><div class="info-card"><div class="card-body text-center py-5 text-muted"><i class="bi bi-newspaper d-block fs-1 mb-2"></i>Belum ada berita tersimpan.<div class="small mt-2">Administrator perlu mengaktifkan API key dan menjalankan sinkronisasi GNews.</div></div></div></div>@endforelse
    </div>
    <div class="mt-4">{{ $news->links() }}</div>
</div>
@endsection
