@extends('layouts.app')
@section('title', 'Dashboard Monitoring')
@section('content')
<div class="welcome-section">
    <h2>Dashboard Monitoring</h2>
    <p>Selamat datang, {{ auth()->user()->name }}. Pantau negara favorit dan perkembangan risiko rantai pasok.</p>
    <div class="welcome-time">
        <i class="bi bi-clock"></i> {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
    </div>
<div class="row g-3 mb-4">@foreach([['Negara Dipantau',
    $watchlistCount,'bi-binoculars','accent'],['Negara Tersedia',
    $countries,'bi-globe2','navy']] as [$label,$value,$icon,$color])
    <div class="col-md-6">
        <div class="stat-card h-100">
            <div class="stat-icon-box stat-icon-{{ $color }}">
                <i class="bi {{ $icon }}"></i>
            </div>
            <div class="stat-label">{{ $label }}</div>
            <div class="stat-value">{{ $value }}</div>
        </div>
    </div>
    @endforeach
</div>
<div class="info-card mb-4"><div class="card-header-navy">
    <i class="bi bi-bar-chart-line"></i>Grafik Risiko Watchlist</div>
    <div class="row mt-4">

<div class="col-md-4">

<div class="alert alert-success">

<i class="bi bi-check-circle-fill"></i>

Negara Dipantau

<h4>{{ $watchlistCount }}</h4>

</div>

</div>

<div class="col-md-4">

<div class="alert alert-primary">

<i class="bi bi-globe"></i>

Total Negara

<h4>{{ $countries }}</h4>

</div>

</div>

<div class="col-md-4">

<div class="alert alert-warning">

<i class="bi bi-bar-chart-fill"></i>

Data Risiko Aktif

<h4>{{ $riskChart->count() }}</h4>

</div>

</div>

</div>
    <div class="card-body">@if($riskChart->isNotEmpty())
        <div style="height:180px">
            <canvas id="watchlistRiskChart"></canvas>
        </div>
        @else
        <div class="text-center text-muted py-5">
            <i class="bi bi-bar-chart fs-1 d-block mb-2"></i>Tambahkan negara ke watchlist dan hitung skor risikonya untuk menampilkan grafik.
        </div>
        @endif
    </div>
</div>
<div class="row g-4">
    <div class="col-xl-7">
        <div class="info-card h-100">
            <div class="card-header-navy">
                <i class="bi bi-star"></i>Watchlist Saya</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Negara</th>
                                    <th>Wilayah</th>
                                    <th>Risiko</th>
                                    <th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($watchlists as $item)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $item->country?->name }}</td>
                                    <td>{{ $item->country?->region }}</td>
                                    <td>@if($item->country?->riskScore)
                                        <span class="badge text-bg-{{ $item->country->riskScore->category === 
                                        'High' ? 'danger' : ($item->country->riskScore->category === 
                                        'Medium' ? 'warning' : 'success') }}">
                                        {{ number_format($item->country->riskScore->total_score,2) }} 
                                        {{ $item->country->riskScore->category }}
                                    </span>
                                    @else
                                    <span class="text-muted">Belum dihitung</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('countries.show',$item->country) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada negara dalam watchlist.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-body border-top">
                <a href="{{ route('watchlist.index') }}" class="btn btn-primary btn-sm">Kelola Watchlist</a>
            </div>
        </div>
    </div>
<div class="col-xl-5">
    <div class="info-card h-100">
        <div class="card-header-navy">
            <i class="bi bi-newspaper"></i>Berita Terbaru</div>
            <div class="card-body">
                @forelse($latestNews as $article)
                <a href="{{ route('news.show',$article) }}" class="d-block text-decoration-none border-bottom pb-3 mb-3">
                    <div class="fw-semibold text-dark">{{ str($article->title)->limit(75) }}</div>
                    <small class="text-muted">
                        {{ $article->country?->name ?? 'Global' }} · 
                        {{ $article->published_at?->diffForHumans() }}
                    </small>
                </a>
                @empty
                <p class="text-center text-muted py-4">Belum ada berita.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
<div class="row g-3 mt-1">
    <div class="col-md-3">
        <a class="quick-action" href="{{ route('countries.index') }}">
            <div class="qa-icon stat-icon-navy">
                <i class="bi bi-globe"></i>
            </div>
            <div class="qa-label">Jelajahi Negara</div>
        </a>
    </div>
    <div class="col-md-3">
        <a class="quick-action" href="{{ route('risk.index') }}">
            <div class="qa-icon stat-icon-danger">
                <i class="bi bi-shield"></i>
            </div>
            <div class="qa-label">Risk Score</div>
        </a>
    </div>
    <div class="col-md-3">
        <a class="quick-action" href="{{ route('comparison.index') }}">
            <div class="qa-icon stat-icon-accent">
                <i class="bi bi-columns-gap"></i>
            </div>
            <div class="qa-label">Perbandingan</div>
        </a>
    </div>
    <div class="col-md-3">
        <a class="quick-action" href="{{ route('news.index') }}">
            <div class="qa-icon stat-icon-warning">
                <i class="bi bi-newspaper"></i>
            </div>
            <div class="qa-label">News Intelligence</div>
        </a>
    </div>
</div>
<div class="info-card mt-4">

    <div class="card-header-navy">
        <i class="bi bi-binoculars"></i>
        Ringkasan Watchlist
    </div>

    <div class="card-body">

        <table class="table table-hover">

            <thead>

                <tr>
                    <th>Negara</th>
                    <th>Risk Score</th>
                    <th>Status</th>
                </tr>

            </thead>

            <tbody>

            @forelse($watchlists as $item)

                <tr>

                    <td>{{ $item->country->name }}</td>

                    <td>{{ optional($item->country->riskScore)->total_score ?? '-' }}</td>

                    <td>

                        @if($item->country?->riskScore)

                        <span class="badge text-bg-{{ $item->country->riskScore->category=='High' ? 'danger' : ($item->country->riskScore->category=='Medium' ? 'warning':'success') }}">

                            {{ $item->country->riskScore->category }}

                        </span>

                        @else

                        <span class="badge bg-secondary">

                            Belum dihitung

                        </span>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="3" class="text-center">

                        Belum ada data watchlist.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>
@endsection
@if($riskChart->isNotEmpty())
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
const watchlistRisk=@json($riskChart);
new Chart(document.getElementById('watchlistRiskChart'),{
    type:'bar',
    data:{
        labels:watchlistRisk.map(item=>item.country),
        datasets:[{
            label:'Skor Risiko',
            data:watchlistRisk.map(item=>item.score),
            backgroundColor:watchlistRisk.map(item=>item.category==='High'?'#ef4444':(item.category==='Medium'?'#f59e0b':'#10b981')),
            borderRadius:7
        }]
    },
    options:{responsive:true,maintainAspectRatio:false,scales:{y:{beginAtZero:true,max:100}},plugins:{legend:{display:false}}}
});
</script>
@endpush
@endif
