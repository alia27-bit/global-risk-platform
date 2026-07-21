{{-- resources/views/dashboard/user.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Monitoring')

@section('content')
<div class="container-fluid">
    <div class="welcome-section mb-4">
        <h2>Dashboard Monitoring</h2>
        <p>Selamat datang, {{ auth()->user()->name }}.
            Pantau negara favorit dan perkembangan risiko rantai pasok.
        </p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-label">Negara Favorit</div>
                <div class="stat-value">{{ $watchlistCount }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-label">Total Negara</div>
                <div class="stat-value">{{ $countries }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-label">Data Risiko</div>
                <div class="stat-value">{{ $riskChart->count() }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="info-card h-100">
                <div class="card-header-navy"><i class="bi bi-graph-up"></i> GDP Trend</div>
                <div class="card-body"><canvas id="gdpTrendChart"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="info-card h-100">
                <div class="card-header-navy"><i class="bi bi-percent"></i> Inflation Trend</div>
                <div class="card-body"><canvas id="inflationTrendChart"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="info-card h-100">
                <div class="card-header-navy"><i class="bi bi-currency-exchange"></i> Currency Trend</div>
                <div class="card-body"><canvas id="currencyTrendChart"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="info-card h-100">
                <div class="card-header-navy"><i class="bi bi-shield-exclamation"></i> Risk Trend</div>
                <div class="card-body"><canvas id="riskTrendChart"></canvas></div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="info-card">
                <div class="card-header-navy">Grafik Risiko Negara Favorit</div>
                <div class="card-body">
                    <canvas id="watchlistRiskChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="info-card">
                <div class="card-header-navy">Kategori Risiko</div>
                <div class="card-body">
                    <canvas id="riskPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="info-card mb-4">
        <div class="card-header-navy d-flex justify-content-between align-items-center">
            <span>Favorite Monitoring</span>
            <a href="{{ route('favorite-monitoring.index') }}" class="btn btn-sm btn-light">Kelola</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Negara</th>
                        <th>Wilayah</th>
                        <th>Risk Score</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($watchlists as $item)
                        <tr>
                            <td>{{ $item->country?->name }}</td>
                            <td>{{ $item->country?->region }}</td>
                            <td>{{ optional($item->country?->riskScore)->total_score ?? '-' }}</td>
                            <td>
                                @if ($item->country?->riskScore)
                                    <span class="badge text-bg-{{
                                        $item->country->riskScore->category == 'High'
                                            ? 'danger'
                                            : ($item->country->riskScore->category == 'Medium' ? 'warning' : 'success')
                                    }}">
                                        {{ $item->country->riskScore->category }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Belum dihitung</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada negara favorit. Tambahkan dari halaman Favorite Monitoring.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const watchlistRisk = @json($riskChart);
const trendCharts = @json($trendCharts);

const palette = ['#0d6efd', '#198754', '#dc3545', '#ffc107', '#6f42c1', '#20c997', '#fd7e14', '#6610f2'];

function buildTrendChart(canvasId, history, fallbackLabels, fallbackData, label) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const hasHistory = history.datasets && history.datasets.length && history.datasets.some(d => d.data.length);
    const config = hasHistory
        ? {
            type: 'line',
            data: {
                labels: history.labels,
                datasets: history.datasets.map((dataset, index) => ({
                    label: dataset.label,
                    data: dataset.data,
                    borderColor: palette[index % palette.length],
                    backgroundColor: palette[index % palette.length],
                    tension: 0.35,
                    fill: false,
                })),
            },
            options: { responsive: true },
        }
        : {
            type: 'bar',
            data: {
                labels: fallbackLabels.length ? fallbackLabels : ['No data'],
                datasets: [{
                    label,
                    data: fallbackData.length ? fallbackData : [0],
                    backgroundColor: palette[0],
                }],
            },
            options: { responsive: true, plugins: { legend: { display: false } } },
        };

    new Chart(canvas, config);
}

buildTrendChart('gdpTrendChart', trendCharts.history.gdp, trendCharts.labels, trendCharts.gdp, 'GDP');
buildTrendChart('inflationTrendChart', trendCharts.history.inflation, trendCharts.labels, trendCharts.inflation, 'Inflation');
buildTrendChart('currencyTrendChart', trendCharts.history.currency, trendCharts.labels, trendCharts.currency, 'Currency');
buildTrendChart('riskTrendChart', trendCharts.history.risk, trendCharts.labels, trendCharts.risk, 'Risk Score');

new Chart(document.getElementById('watchlistRiskChart'), {
    type: 'bar',
    data: {
        labels: watchlistRisk.map(x => x.country),
        datasets: [{
            label: 'Risk Score',
            data: watchlistRisk.map(x => x.score),
            backgroundColor: watchlistRisk.map(x =>
                x.category === 'High' ? '#dc3545' : (x.category === 'Medium' ? '#ffc107' : '#198754')
            ),
        }],
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, max: 100 } },
    },
});

new Chart(document.getElementById('riskPieChart'), {
    type: 'pie',
    data: {
        labels: ['High', 'Medium', 'Low'],
        datasets: [{
            data: [
                {{ $riskCategory['High'] }},
                {{ $riskCategory['Medium'] }},
                {{ $riskCategory['Low'] }},
            ],
            backgroundColor: ['#dc3545', '#ffc107', '#198754'],
        }],
    },
});
</script>
@endpush
