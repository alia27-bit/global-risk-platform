@extends('layouts.app')

@section('title', 'Risk Detail - '.$country->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            @if ($country->flag)<img src="{{ $country->flag }}" width="58" class="rounded shadow-sm" alt="{{ $country->name }}">@endif
            <div><h3 class="mb-1">{{ $country->name }}</h3><p class="text-muted mb-0">Risk assessment · {{ $country->code }} · {{ $country->region }}</p></div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('risk.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
            @if(auth()->user()->peran === 'admin')<a href="{{ route('risk.calculate', $country) }}" class="btn btn-danger"><i class="bi bi-calculator me-1"></i>Hitung Ulang</a>@endif
        </div>
    </div>

    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    @if (! $risk)
        <div class="info-card"><div class="card-body text-center py-5"><i class="bi bi-shield-exclamation fs-1 text-muted"></i><h5 class="mt-3">Risk score belum tersedia</h5><p class="text-muted">Administrator belum menjalankan perhitungan risiko.</p>@if(auth()->user()->peran === 'admin')<a href="{{ route('risk.calculate', $country) }}" class="btn btn-danger">Hitung Sekarang</a>@endif</div></div>
    @else
        @php($badge = $risk->category === 'High' ? 'danger' : ($risk->category === 'Medium' ? 'warning' : 'success'))
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="info-card h-100"><div class="card-header-navy"><i class="bi bi-speedometer"></i>Total Risk</div><div class="card-body text-center py-5"><div class="display-3 fw-bold text-{{ $badge }}">{{ number_format($risk->total_score, 2) }}</div><span class="badge text-bg-{{ $badge }} fs-6 mt-2">{{ $risk->category }} Risk</span><p class="text-muted mt-3 mb-0">Skala 0–100</p></div></div>
            </div>
            <div class="col-lg-8">
                <div class="info-card h-100"><div class="card-header-navy"><i class="bi bi-bar-chart"></i>Komponen Weighted Risk</div><div class="card-body"><canvas id="riskComponents" height="260"></canvas></div></div>
            </div>
        </div>
        <div class="info-card mt-4"><div class="card-header-navy"><i class="bi bi-database-check"></i>Data Dasar Perhitungan</div><div class="card-body"><div class="row g-3">
            <div class="col-md-4"><small class="text-muted d-block">Cuaca</small><strong>{{ $country->weather ? number_format($country->weather->temperature, 1).' °C · Wind '.number_format($country->weather->wind_speed, 1).' km/h' : 'Data belum tersedia' }}</strong></div>
            <div class="col-md-4"><small class="text-muted d-block">Inflasi</small><strong>{{ $country->economicIndicator?->inflation !== null ? number_format($country->economicIndicator->inflation, 2).'%' : 'Data belum tersedia' }}</strong></div>
            <div class="col-md-4"><small class="text-muted d-block">Kurs</small><strong>{{ $country->exchangeRate ? $country->exchangeRate->base_currency.'/'.$country->exchangeRate->target_currency.' = '.number_format($country->exchangeRate->exchange_rate, 4) : 'Data belum tersedia' }}</strong></div>
        </div></div></div>
        <div class="alert alert-light border mt-4 mb-0"><strong>Rumus:</strong> Total = (Weather × 30%) + (Inflation × 20%) + (Currency volatility × 10%) + (News sentiment × 40%). <span class="text-success">Low &lt; 30</span>, <span class="text-warning">Medium 30–&lt;60</span>, <span class="text-danger">High ≥ 60</span>. Semua komponen dinormalisasi ke skala 0–100.</div>
        <div class="info-card mt-4"><div class="card-header-navy"><i class="bi bi-graph-up-arrow"></i>Risk Trend</div><div class="card-body"><canvas id="riskTrend" height="280"></canvas></div></div>
    @endif
</div>
@endsection

@if ($risk)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('riskComponents'), { type: 'bar', data: { labels: ['Weather (30%)', 'Economic (20%)', 'Currency (10%)', 'News (40%)'], datasets: [{ label: 'Score', data: [{{ $risk->weather_score }}, {{ $risk->economic_score }}, {{ $risk->currency_score }}, {{ $risk->news_score }}], backgroundColor: ['#3b82f6','#f59e0b','#8b5cf6','#ef4444'], borderRadius: 6 }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100 } }, plugins: { legend: { display: false } } } });
new Chart(document.getElementById('riskTrend'), { type: 'line', data: { labels: @json($history->map(fn($x)=>$x->created_at->format('d/m H:i'))), datasets: [{ label: 'Total Risk', data: @json($history->pluck('total_score')->map(fn($x)=>(float)$x)), borderColor:'#ef4444',backgroundColor:'rgba(239,68,68,.12)',fill:true,tension:.3 }] }, options:{responsive:true,maintainAspectRatio:false,scales:{y:{beginAtZero:true,max:100}}} });
</script>
@endpush
@endif
