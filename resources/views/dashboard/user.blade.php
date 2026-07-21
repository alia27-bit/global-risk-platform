{{-- resources/views/dashboard/user.blade.php --}}
@extends('layouts.app')

@section('title','Dashboard Monitoring')

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
                <div class="stat-label">Negara Dipantau</div>
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

        <div class="info-card">

            <div class="card-header-navy">
                <i class="bi bi-pie-chart-fill"></i>
                Kategori Risiko
            </div>

            <div class="card-body">

                <canvas id="riskPieChart"></canvas>

            </div>

        </div>

    </div>

    <div class="col-lg-6">

        <div class="info-card">

            <div class="card-header-navy">
                <i class="bi bi-graph-up-arrow"></i>
                Trend Risk Score
            </div>

            <div class="card-body">

                <canvas id="riskLineChart"></canvas>

            </div>

        </div>

    </div>

</div>
    <div class="row g-4 mb-4">

        <div class="col-lg-8">
            <div class="info-card">
                <div class="card-header-navy">
                    Grafik Risiko Watchlist
                </div>
                <div class="card-body">
                    <canvas id="watchlistRiskChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="info-card">
                <div class="card-header-navy">
                    Kategori Risiko
                </div>
                <div class="card-body">
                    <canvas id="riskPieChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    <div class="info-card mb-4">
        <div class="card-header-navy">Watchlist Saya</div>

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

                @forelse($watchlists as $item)

                    <tr>

                        <td>{{ $item->country?->name }}</td>

                        <td>{{ $item->country?->region }}</td>

                        <td>{{ optional($item->country?->riskScore)->total_score ?? '-' }}</td>

                        <td>
                            @if($item->country?->riskScore)
                                <span class="badge text-bg-{{
                                    $item->country->riskScore->category=='High'
                                    ? 'danger'
                                    : ($item->country->riskScore->category=='Medium'
                                        ? 'warning'
                                        : 'success')
                                }}">
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
                        <td colspan="4" class="text-center">
                            Belum ada watchlist.
                        </td>
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

const watchlistRisk=@json($riskChart);

new Chart(document.getElementById('watchlistRiskChart'),{
    type:'bar',
    data:{
        labels:watchlistRisk.map(x=>x.country),
        datasets:[{
            label:'Risk Score',
            data:watchlistRisk.map(x=>x.score),
            backgroundColor:watchlistRisk.map(x=>
                x.category==='High'
                ? '#dc3545'
                : (x.category==='Medium'
                    ? '#ffc107'
                    : '#198754')
            )
        }]
    },
    options:{
        responsive:true,
        plugins:{
            legend:{display:false}
        },
        scales:{
            y:{beginAtZero:true,max:100}
        }
    }
});

new Chart(document.getElementById('riskPieChart'),{
    type:'pie',
    data:{
        labels:['High','Medium','Low'],
        datasets:[{
            data:[
                {{ $riskCategory['High'] }},
                {{ $riskCategory['Medium'] }},
                {{ $riskCategory['Low'] }}
            ],
            backgroundColor:[
                '#dc3545',
                '#ffc107',
                '#198754'
            ]
        }]
    }
});
new Chart(document.getElementById('riskPieChart'),{

    type:'pie',

    data:{

        labels:['High','Medium','Low'],

        datasets:[{

            data:[
                {{ $riskCategory['High'] }},
                {{ $riskCategory['Medium'] }},
                {{ $riskCategory['Low'] }}
            ],

            backgroundColor:[
                '#dc3545',
                '#ffc107',
                '#198754'
            ]

        }]

    }

});


new Chart(document.getElementById('riskLineChart'),{

    type:'line',

    data:{

        labels:watchlistRisk.map(x=>x.country),

        datasets:[{

            label:'Risk Score',

            data:watchlistRisk.map(x=>x.score),

            borderColor:'#0d6efd',

            backgroundColor:'#0d6efd',

            fill:false,

            tension:.4

        }]

    },

    options:{

        responsive:true

    }

});
</script>
@endpush
