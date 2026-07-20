@extends('layouts.app')
@section('title','Ekonomi '.$country->name)
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4"><div><h3>Economic Intelligence</h3><p class="text-muted">{{ $country->name }} &middot; World Bank</p></div><a href="{{ route('countries.show',$country) }}" class="btn btn-outline-secondary">Kembali</a></div>
    <div class="row g-3">
        @foreach([['GDP',$indicator?->gdp === null ? '-' : '$'.number_format($indicator->gdp,0),'bi-bank'],['Inflasi',$indicator?->inflation === null ? '-' : number_format($indicator->inflation,2).'%','bi-graph-up-arrow'],['Pengangguran',$indicator?->unemployment === null ? '-' : number_format($indicator->unemployment,2).'%','bi-person-dash'],['Populasi',number_format($country->population ?? 0),'bi-people'],['Ekspor',$indicator?->exports === null ? '-' : '$'.number_format($indicator->exports,0),'bi-box-arrow-up-right'],['Impor',$indicator?->imports === null ? '-' : '$'.number_format($indicator->imports,0),'bi-box-arrow-in-down']] as [$label,$value,$icon])
        <div class="col-sm-6 col-xl-4"><div class="stat-card h-100"><div class="card-body"><div class="stat-icon-box stat-icon-navy"><i class="bi {{ $icon }}"></i></div><div class="stat-label">{{ $label }}</div><div class="h4">{{ $value }}</div></div></div></div>
        @endforeach
    </div>
    <div class="row g-4 mt-1"><div class="col-lg-7"><div class="info-card h-100"><div class="card-header-navy"><i class="bi bi-bar-chart-line"></i>GDP Trend</div><div class="card-body"><canvas id="gdpTrend" height="280"></canvas></div></div></div><div class="col-lg-5"><div class="info-card h-100"><div class="card-header-navy"><i class="bi bi-graph-up"></i>Inflation Trend</div><div class="card-body"><canvas id="inflationTrend" height="280"></canvas></div></div></div></div>
    <div class="alert alert-info mt-4"><i class="bi bi-info-circle me-2"></i>World Bank menerbitkan indikator ekonomi secara periodik; grafik menyimpan setiap snapshot terbaru yang disinkronkan.</div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
const economicLabels=@json($history->map(fn($x)=>$x->created_at->format('d/m/Y')));
new Chart(document.getElementById('gdpTrend'),{type:'line',data:{labels:economicLabels,datasets:[{label:'GDP (USD)',data:@json($history->pluck('gdp')->map(fn($x)=>(float)$x)),borderColor:'#3b82f6',backgroundColor:'rgba(59,130,246,.12)',fill:true,tension:.3}]},options:{responsive:true,maintainAspectRatio:false}});
new Chart(document.getElementById('inflationTrend'),{type:'line',data:{labels:economicLabels,datasets:[{label:'Inflasi %',data:@json($history->pluck('inflation')->map(fn($x)=>(float)$x)),borderColor:'#f59e0b',backgroundColor:'rgba(245,158,11,.12)',fill:true,tension:.3}]},options:{responsive:true,maintainAspectRatio:false}});
</script>
@endpush
