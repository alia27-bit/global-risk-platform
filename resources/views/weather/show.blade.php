@extends('layouts.app')
@section('title', 'Cuaca '.$country->name)
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4"><div><h3>Global Weather Monitoring</h3><p class="text-muted">{{ $country->name }} &middot; Open-Meteo</p></div><div class="d-flex gap-2"><a href="{{ route('weather.map') }}" class="btn btn-success"><i class="bi bi-map"></i> Peta Global</a><a href="{{ route('countries.show',$country) }}" class="btn btn-outline-secondary">Kembali</a></div></div>
    <div class="info-card"><div class="card-header-navy"><i class="bi bi-cloud-sun"></i>Kondisi Saat Ini</div><div class="card-body">
        @if($weather)
        <div class="row g-4 text-center">
            @foreach([['Temperatur',number_format($weather->temperature,1).' °C','bi-thermometer-half'],['Curah Hujan',number_format($weather->rainfall,1).' mm','bi-cloud-rain'],['Kecepatan Angin',number_format($weather->wind_speed,1).' km/h','bi-wind'],['Risiko Badai',number_format($weather->storm_risk,1).'/100','bi-cloud-lightning']] as [$label,$value,$icon])
            <div class="col-sm-6 col-lg-3"><i class="bi {{ $icon }} fs-2 text-primary"></i><div class="text-muted small mt-2">{{ $label }}</div><div class="h4">{{ $value }}</div></div>
            @endforeach
        </div><p class="text-center text-muted mt-4 mb-0">Observasi: {{ $weather->observed_at?->format('d M Y H:i') ?? $weather->updated_at->format('d M Y H:i') }}</p>
        @else<div class="text-center py-5 text-muted">Data cuaca belum tersedia.</div>@endif
    </div></div>
    <div class="info-card mt-4"><div class="card-header-navy"><i class="bi bi-graph-up"></i>Tren Cuaca</div><div class="card-body"><canvas id="weatherTrend" height="280"></canvas></div></div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('weatherTrend'), {type:'line',data:{labels:@json($history->map(fn($x)=>($x->observed_at ?? $x->created_at)->format('d/m H:i'))),datasets:[{label:'Temperatur °C',data:@json($history->pluck('temperature')->map(fn($x)=>(float)$x)),borderColor:'#ef4444',tension:.3},{label:'Angin km/h',data:@json($history->pluck('wind_speed')->map(fn($x)=>(float)$x)),borderColor:'#3b82f6',tension:.3},{label:'Hujan mm',data:@json($history->pluck('rainfall')->map(fn($x)=>(float)$x)),borderColor:'#10b981',tension:.3}]},options:{responsive:true,maintainAspectRatio:false}});
</script>
@endpush
