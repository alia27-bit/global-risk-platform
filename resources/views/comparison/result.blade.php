@extends('layouts.app')
@section('title','Comparison Result')
@section('content')
@php
$rows = [
 ['GDP', $countryA->economicIndicator?->gdp, $countryB->economicIndicator?->gdp, fn($v) => $v === null ? '-' : '$'.number_format($v, 0)],
 ['Inflasi', $countryA->economicIndicator?->inflation, $countryB->economicIndicator?->inflation, fn($v) => $v === null ? '-' : number_format($v, 2).'%'],
 ['Temperatur', $countryA->weather?->temperature, $countryB->weather?->temperature, fn($v) => $v === null ? '-' : number_format($v, 1).' °C'],
 ['Kecepatan Angin', $countryA->weather?->wind_speed, $countryB->weather?->wind_speed, fn($v) => $v === null ? '-' : number_format($v, 1).' km/h'],
 ['Nilai Tukar', $countryA->exchangeRate?->exchange_rate, $countryB->exchangeRate?->exchange_rate, fn($v) => $v === null ? '-' : number_format($v, 4)],
 ['Risk Score', $countryA->riskScore?->total_score, $countryB->riskScore?->total_score, fn($v) => $v === null ? '-' : number_format($v, 2)],
];
@endphp
<div class="container-fluid"><div class="d-flex justify-content-between align-items-center mb-4"><div><h3 class="mb-1">Hasil Perbandingan</h3><p class="text-muted mb-0">Analisis indikator utama kedua negara.</p></div><a href="{{ route('comparison.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Bandingkan Lagi</a></div>
@if(! $countryA->weather || ! $countryB->weather || ! $countryA->economicIndicator || ! $countryB->economicIndicator)
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Sebagian indikator tidak tersedia dari penyedia data untuk negara yang dipilih.</div>
@endif
<div class="info-card"><div class="card-body p-0"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr class="table-light"><th class="ps-4">Indikator</th>@foreach([$countryA,$countryB] as $country)<th class="text-center py-3">@if($country->flag)<img src="{{ $country->flag }}" width="38" class="rounded me-2">@endif {{ $country->name }}</th>@endforeach</tr></thead><tbody>
@foreach($rows as [$label,$valueA,$valueB,$format])<tr><th class="ps-4">{{ $label }}</th><td class="text-center fs-5">{{ $format($valueA) }}</td><td class="text-center fs-5">{{ $format($valueB) }}</td></tr>@endforeach
<tr><th class="ps-4">Mata Uang</th><td class="text-center">{{ $countryA->currency_code ?: '-' }}</td><td class="text-center">{{ $countryB->currency_code ?: '-' }}</td></tr>
<tr><th class="ps-4">Kategori Risiko</th><td class="text-center">{{ $countryA->riskScore?->category ?? 'Belum dihitung' }}</td><td class="text-center">{{ $countryB->riskScore?->category ?? 'Belum dihitung' }}</td></tr>
</tbody></table></div></div></div>
<div class="row g-4 mt-1"><div class="col-lg-7"><div class="info-card h-100"><div class="card-header-navy"><i class="bi bi-bar-chart"></i>Risk &amp; Operational Comparison</div><div class="card-body"><canvas id="comparisonChart" height="260"></canvas></div></div></div><div class="col-lg-5"><div class="info-card h-100"><div class="card-header-navy"><i class="bi bi-bank"></i>GDP Comparison</div><div class="card-body"><canvas id="gdpComparison" height="260"></canvas></div></div></div></div></div>
@endsection
@push('scripts')<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script><script>
const countries=@json([$countryA->name,$countryB->name]);
new Chart(document.getElementById('comparisonChart'),{type:'bar',data:{labels:['Inflasi %','Weather Risk','Currency Risk','Total Risk'],datasets:[{label:@json($countryA->name),data:[{{ $countryA->economicIndicator?->inflation ?? 0 }},{{ $countryA->riskScore?->weather_score ?? 0 }},{{ $countryA->riskScore?->currency_score ?? 0 }},{{ $countryA->riskScore?->total_score ?? 0 }}],backgroundColor:'#3b82f6'},{label:@json($countryB->name),data:[{{ $countryB->economicIndicator?->inflation ?? 0 }},{{ $countryB->riskScore?->weather_score ?? 0 }},{{ $countryB->riskScore?->currency_score ?? 0 }},{{ $countryB->riskScore?->total_score ?? 0 }}],backgroundColor:'#f59e0b'}]},options:{responsive:true,maintainAspectRatio:false,scales:{y:{beginAtZero:true,max:100}}}});
new Chart(document.getElementById('gdpComparison'),{type:'bar',data:{labels:countries,datasets:[{label:'GDP (USD)',data:[{{ $countryA->economicIndicator?->gdp ?? 0 }},{{ $countryB->economicIndicator?->gdp ?? 0 }}],backgroundColor:['#3b82f6','#f59e0b'],borderRadius:8}]},options:{responsive:true,maintainAspectRatio:false,indexAxis:'y',plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>new Intl.NumberFormat('id-ID',{style:'currency',currency:'USD',notation:'compact'}).format(c.raw)}}},scales:{x:{beginAtZero:true,ticks:{callback:v=>Intl.NumberFormat('id-ID',{notation:'compact'}).format(v)}}}}});
</script>@endpush
