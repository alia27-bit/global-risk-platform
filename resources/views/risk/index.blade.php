@extends('layouts.app')

@section('title', 'Risk Intelligence')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="mb-1">Supply Chain Risk Intelligence</h3>
            <p class="text-muted mb-0">Weighted model: Weather 30%, Inflation 20%, Currency 10%, News 40%.</p>
        </div>
        @if(auth()->user()->peran === 'admin')
        <form id="calculateRiskForm" class="d-flex gap-2">
            <select id="riskCountry" class="form-select" required>
                <option value="">Pilih negara...</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }} ({{ $country->code }})</option>
                @endforeach
            </select>
            <button class="btn btn-danger text-nowrap" type="submit">
                <i class="bi bi-calculator me-1"></i> Hitung Risk
            </button>
        </form>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        @foreach ([
            ['label' => __('messages.countries_assessed'), 'value' => $summary['total'], 'icon' => 'bi-globe2', 'class' => 'navy'],
            ['label' => __('messages.low_risk'), 'value' => $summary['low'], 'icon' => 'bi-shield-check', 'class' => 'success'],
            ['label' => __('messages.medium_risk'), 'value' => $summary['medium'], 'icon' => 'bi-exclamation-triangle', 'class' => 'warning'],
            ['label' => __('messages.high_risk'), 'value' => $summary['high'], 'icon' => 'bi-shield-exclamation', 'class' => 'danger'],
        ] as $card)
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card h-100">
                    <div class="stat-icon-box stat-icon-{{ $card['class'] }}"><i class="bi {{ $card['icon'] }}"></i></div>
                    <div class="stat-label">{{ $card['label'] }}</div>
                    <div class="stat-value">{{ $card['value'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="info-card h-100">
                <div class="card-header-navy"><i class="bi bi-table"></i> Peringkat Risiko Negara</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Negara</th>
                                    <th>Weather</th>
                                    <th>Economic</th>
                                    <th>Currency</th>
                                    <th>News</th>
                                    <th>Total</th>
                                    <th class="pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riskScores as $risk)
                                    @php($badge = $risk->category === 'High' ? 'danger' : ($risk->category === 'Medium' ? 'warning' : 'success'))
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-semibold">{{ $risk->country?->name ?? '-' }}</div>
                                            <small class="text-muted">{{ $risk->country?->code }}</small>
                                        </td>
                                        <td>{{ number_format($risk->weather_score, 1) }}</td>
                                        <td>{{ number_format($risk->economic_score, 1) }}</td>
                                        <td>{{ number_format($risk->currency_score, 1) }}</td>
                                        <td>{{ number_format($risk->news_score, 1) }}</td>
                                        <td><span class="badge text-bg-{{ $badge }}">{{ number_format($risk->total_score, 2) }} · {{ $risk->category }}</span></td>
                                        <td class="pe-4 text-nowrap">
                                            @if ($risk->country)
                                                <a href="{{ route('risk.show', $risk->country) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="bi bi-eye"></i></a>
                                                @if(auth()->user()->peran === 'admin')<a href="{{ route('risk.calculate', $risk->country) }}" class="btn btn-sm btn-outline-danger" title="Hitung ulang"><i class="bi bi-arrow-repeat"></i></a>@endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-clipboard-data d-block fs-1 mb-2"></i>Belum ada risk score. Pilih negara lalu klik Hitung Risk.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($riskScores->hasPages())
                        <div class="p-3 border-top">{{ $riskScores->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-card">
                <div class="card-header-navy"><i class="bi bi-pie-chart"></i> Distribusi Risiko</div>
                <div class="card-body"><canvas id="riskDistribution" height="260"></canvas></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    document.getElementById('calculateRiskForm')?.addEventListener('submit', function (event) {
        event.preventDefault();
        const id = document.getElementById('riskCountry').value;
        if (id) window.location.href = @json(url('/risk')) + '/' + id + '/calculate';
    });

    new Chart(document.getElementById('riskDistribution'), {
        type: 'doughnut',
        data: {
            labels: [@json(__('messages.low_risk')), @json(__('messages.medium_risk')), @json(__('messages.high_risk'))],
            datasets: [{ data: [{{ $summary['low'] }}, {{ $summary['medium'] }}, {{ $summary['high'] }}], backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], borderWidth: 0 }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
    });
</script>
@endpush
