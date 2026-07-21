@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
<div class="welcome-section"><h2>Dashboard Administrator</h2><p>Selamat datang, {{ auth()->user()->name }}. Kelola data, integrasi API, dan analitik platform.</p><div class="welcome-time"><i class="bi bi-clock"></i> {{ now()->translatedFormat('d F Y, H:i') }} WIB</div></div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
@foreach([['Negara',$countries,'bi-globe2','navy'],['Pelabuhan',$ports,'bi-geo-alt','success'],['Berita',$newsCount,'bi-newspaper','warning'],['Pengguna',$users,'bi-people','accent']] as [$label,$value,$icon,$color])
<div class="col-sm-6 col-xl-3"><div class="stat-card h-100"><div class="stat-icon-box stat-icon-{{ $color }}"><i class="bi {{ $icon }}"></i></div><div class="stat-label">Total {{ $label }}</div><div class="stat-value">{{ $value }}</div></div></div>
@endforeach
</div>

{{-- Risk Distribution + Top Risk Score --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="info-card">
            <div class="card-header-navy">
                <i class="bi bi-pie-chart-fill"></i> Distribusi Risiko
            </div>
            <div class="card-body">
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="alert alert-danger">
                            <strong>High Risk</strong><br>
                            {{ $riskSummary['High'] ?? 0 }} Negara
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-warning">
                            <strong>Medium Risk</strong><br>
                            {{ $riskSummary['Medium'] ?? 0 }} Negara
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-success">
                            <strong>Low Risk</strong><br>
                            {{ $riskSummary['Low'] ?? 0 }} Negara
                        </div>
                    </div>
                </div>
                <canvas id="riskPieChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="info-card">
            <div class="card-header-navy">
                <i class="bi bi-bar-chart-fill"></i> Top Risk Score
            </div>
            <div class="card-body">
                <canvas id="riskBarChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Trend Charts: GDP, Inflation, Currency, Risk --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="info-card h-100">
            <div class="card-header-navy"><i class="bi bi-graph-up"></i> GDP Trend</div>
            <div class="card-body"><canvas id="gdpChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="info-card h-100">
            <div class="card-header-navy"><i class="bi bi-percent"></i> Inflation Trend</div>
            <div class="card-body"><canvas id="inflationChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="info-card h-100">
            <div class="card-header-navy"><i class="bi bi-currency-exchange"></i> Currency Trend</div>
            <div class="card-body"><canvas id="currencyChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="info-card h-100">
            <div class="card-header-navy"><i class="bi bi-shield-exclamation"></i> Risk Trend</div>
            <div class="card-body"><canvas id="riskTrendChart"></canvas></div>
        </div>
    </div>
</div>

{{-- Top Risks Table + Quick Actions --}}
<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="info-card h-100">
            <div class="card-header-navy"><i class="bi bi-shield-exclamation"></i> Negara dengan Risiko Tertinggi</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Negara</th>
                                <th>Weather</th>
                                <th>Economic</th>
                                <th>News</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topRisks as $risk)
                            <tr>
                                <td class="ps-4">{{ $risk->country?->name }}</td>
                                <td>{{ number_format($risk->weather_score, 1) }}</td>
                                <td>{{ number_format($risk->economic_score, 1) }}</td>
                                <td>{{ number_format($risk->news_score, 1) }}</td>
                                <td>
                                    <div class="progress mb-2" style="height:8px">
                                        <div class="progress-bar bg-{{ $risk->category == 'High' ? 'danger' : ($risk->category == 'Medium' ? 'warning' : 'success') }}"
                                             style="width:{{ min($risk->total_score, 100) }}%"></div>
                                    </div>
                                    <span class="badge text-bg-{{ $risk->category == 'High' ? 'danger' : ($risk->category == 'Medium' ? 'warning' : 'success') }}">
                                        {{ number_format($risk->total_score, 2) }} {{ $risk->category }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada risk score.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="info-card h-100">
            <div class="card-header-navy">
                <i class="bi bi-lightning"></i> Akses Cepat Admin
            </div>
            <div class="card-body d-grid gap-2">
                <a class="quick-action" href="{{ route('countries.index') }}">
                    <div class="qa-icon stat-icon-navy"><i class="bi bi-globe"></i></div>
                    <div>
                        <div class="qa-label">Kelola Negara &amp; API</div>
                        <div class="qa-desc">Sinkronkan seluruh sumber data</div>
                    </div>
                </a>
                <a class="quick-action" href="{{ route('ports.index') }}">
                    <div class="qa-icon stat-icon-success"><i class="bi bi-geo-alt"></i></div>
                    <div>
                        <div class="qa-label">Dataset Pelabuhan</div>
                        <div class="qa-desc">CRUD dan peta Leaflet</div>
                    </div>
                </a>
                <a class="quick-action" href="{{ route('news.index') }}">
                    <div class="qa-icon stat-icon-warning"><i class="bi bi-newspaper"></i></div>
                    <div>
                        <div class="qa-label">News Intelligence</div>
                        <div class="qa-desc">Artikel dan sentimen</div>
                    </div>
                </a>
                <a class="quick-action" href="{{ route('risk.index') }}">
                    <div class="qa-icon stat-icon-danger"><i class="bi bi-shield"></i></div>
                    <div>
                        <div class="qa-label">Risk Engine</div>
                        <div class="qa-desc">Hitung dan pantau risiko</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- API Logs --}}
<div class="info-card mb-4">
    <div class="card-header-navy">
        <i class="bi bi-activity"></i> Status Integrasi API
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">API</th>
                        <th>Status</th>
                        <th>Pesan</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($apiLogs as $log)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ $log->api_name }}</td>
                        <td>
                            <span class="badge text-bg-{{ $log->status_code >= 200 && $log->status_code < 300 ? 'success' : ($log->status_code >= 400 ? 'danger' : 'warning') }}">
                                {{ $log->status_code }}
                            </span>
                        </td>
                        <td>{{ str($log->message)->limit(80) }}</td>
                        <td>{{ $log->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada aktivitas API.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<hr>
<div class="text-center text-muted py-3">
    Global Supply Chain Risk Intelligence Platform<br>
    Version 1.0<br>
    &copy; {{ date('Y') }}
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// --- Risk Pie Chart ---
const riskSummary = @json($riskSummary);
new Chart(document.getElementById('riskPieChart'), {
    type: 'pie',
    data: {
        labels: Object.keys(riskSummary),
        datasets: [{
            data: Object.values(riskSummary),
            backgroundColor: ['#28a745', '#ffc107', '#dc3545']
        }]
    }
});

// --- Risk Bar Chart ---
const risk = @json($riskChart);
new Chart(document.getElementById('riskBarChart'), {
    type: 'bar',
    data: {
        labels: risk.map(x => x.country.name),
        datasets: [{
            label: 'Risk Score',
            data: risk.map(x => x.total_score),
            backgroundColor: '#0d6efd'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } }
    }
});

// --- GDP Trend ---
const gdp = @json($gdpChart);
new Chart(document.getElementById('gdpChart'), {
    type: 'line',
    data: {
        labels: gdp.map(x => x.name),
        datasets: [{
            label: 'GDP',
            data: gdp.map(x => x.economic_indicator ? x.economic_indicator.gdp : 0),
            fill: false,
            borderColor: '#198754',
            tension: 0.4
        }]
    },
    options: { responsive: true }
});

// --- Inflation Trend ---
const inflationData = @json($inflationChart);
new Chart(document.getElementById('inflationChart'), {
    type: 'line',
    data: {
        labels: inflationData.map(x => x.name),
        datasets: [{
            label: 'Inflation (%)',
            data: inflationData.map(x => x.economic_indicator ? x.economic_indicator.inflation : 0),
            fill: false,
            borderColor: '#fd7e14',
            tension: 0.4
        }]
    },
    options: { responsive: true }
});

// --- Currency Trend ---
const currencyData = @json($currencyChart);
new Chart(document.getElementById('currencyChart'), {
    type: 'line',
    data: {
        labels: currencyData.map(x => x.name),
        datasets: [{
            label: 'Exchange Rate',
            data: currencyData.map(x => x.exchange_rate ? x.exchange_rate.exchange_rate : 0),
            fill: false,
            borderColor: '#6f42c1',
            tension: 0.4
        }]
    },
    options: { responsive: true }
});

// --- Risk Trend ---
const riskTrend = @json($riskTrendChart);
new Chart(document.getElementById('riskTrendChart'), {
    type: 'bar',
    data: {
        labels: riskTrend.map(x => x.country.name),
        datasets: [{
            label: 'Risk Score',
            data: riskTrend.map(x => x.total_score),
            backgroundColor: riskTrend.map(x =>
                x.category === 'High' ? '#dc3545' : (x.category === 'Medium' ? '#ffc107' : '#198754')
            )
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, max: 100 } }
    }
});
</script>
@endpush
