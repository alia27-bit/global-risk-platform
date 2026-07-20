@extends('layouts.app')
@section('title','Peta Cuaca Global')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4"><div><h3>Global Weather Map</h3><p class="text-muted mb-0">Temperatur, hujan, angin, dan risiko badai dari Open-Meteo.</p></div><a href="{{ route('countries.index') }}" class="btn btn-outline-secondary">Daftar Negara</a></div>
    <div class="row g-3 mb-3"><div class="col-sm-3"><div class="stat-card"><div class="card-body"><div class="stat-label">Marker aktif</div><div id="markerCount" class="h3 mb-0">0</div></div></div></div><div class="col-sm-9"><div class="alert alert-success mb-0 h-100 d-flex align-items-center"><i class="bi bi-broadcast me-2"></i><span id="weatherStatus">Mengambil data cuaca terbaru...</span></div></div></div>
    <div class="d-flex flex-wrap gap-2 mb-3" id="weatherFilters"><button class="btn btn-primary btn-sm" data-filter="all">Semua</button><button class="btn btn-outline-primary btn-sm" data-filter="rain"><i class="bi bi-cloud-rain"></i> Hujan</button><button class="btn btn-outline-primary btn-sm" data-filter="wind"><i class="bi bi-wind"></i> Angin Kencang</button><button class="btn btn-outline-primary btn-sm" data-filter="storm"><i class="bi bi-cloud-lightning"></i> Badai</button><span class="ms-auto small text-muted align-self-center"><i class="bi bi-circle-fill text-success"></i> Rendah &nbsp;<i class="bi bi-circle-fill text-warning"></i> Waspada &nbsp;<i class="bi bi-circle-fill text-danger"></i> Tinggi</span></div>
    <div class="info-card"><div class="card-body p-0"><div id="weatherMap" style="height:650px"></div></div></div>
</div>
@endsection
@push('styles')<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">@endpush
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const weatherMap=L.map('weatherMap').setView([10,110],2);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'&copy; OpenStreetMap contributors'}).addTo(weatherMap);
const weatherLayer=L.layerGroup().addTo(weatherMap); let weatherItems=[]; let activeFilter='all';
function renderWeather(){
    weatherLayer.clearLayers(); let shown=0;
    weatherItems.forEach(item=>{
        if(!Number.isFinite(item.lat)||!Number.isFinite(item.lng))return;
        const matches=activeFilter==='all'||(activeFilter==='rain'&&item.rainfall>0)||(activeFilter==='wind'&&item.wind_speed>=40)||(activeFilter==='storm'&&(item.storm_risk>=30||item.weather_code>=95));
        if(!matches)return; shown++;
        const color=item.storm_risk>=60?'#ef4444':(item.storm_risk>=30?'#f59e0b':'#10b981'); const radius=Math.min(18,8+(item.storm_risk/20));
        L.circleMarker([item.lat,item.lng],{radius,color,weight:2,fillColor:color,fillOpacity:.72}).addTo(weatherLayer).bindPopup(`<strong>${item.country}</strong><br>Temperatur: ${item.temperature} °C<br>Hujan: ${item.rainfall} mm<br>Angin: ${item.wind_speed} km/h<br>Risiko badai: ${item.storm_risk}/100<br><small>${new Date(item.observed_at).toLocaleString('id-ID')}</small><br><a href="${item.url}">Detail cuaca</a>`);
    }); document.getElementById('markerCount').textContent=shown;
}
async function loadWeather(){
    try{const response=await fetch('{{ url('/api/v1/live') }}',{headers:{Accept:'application/json','X-Requested-With':'XMLHttpRequest'}});if(!response.ok)throw new Error('HTTP '+response.status);const payload=await response.json();weatherItems=payload.weather;renderWeather();document.getElementById('weatherStatus').textContent='Data AJAX diperbarui '+new Date(payload.server_time).toLocaleTimeString('id-ID');}
    catch(error){document.getElementById('weatherStatus').textContent='Pembaruan gagal: '+error.message;}
}
document.querySelectorAll('#weatherFilters [data-filter]').forEach(button=>button.addEventListener('click',()=>{activeFilter=button.dataset.filter;document.querySelectorAll('#weatherFilters [data-filter]').forEach(item=>{item.classList.toggle('btn-primary',item===button);item.classList.toggle('btn-outline-primary',item!==button)});renderWeather()}));
loadWeather(); window.setInterval(loadWeather,60000);
</script>
@endpush
