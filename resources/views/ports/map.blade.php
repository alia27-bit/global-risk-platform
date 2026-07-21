@extends('layouts.app')

@section('title', 'Peta Pelabuhan')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3>Port Location Dashboard</h3>
            <p class="text-muted mb-0">{{ $ports->count() }} pelabuhan · Leaflet.js & OpenStreetMap · World Port Index</p>
        </div>
        <a href="{{ route('ports.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-list-ul"></i> Daftar Pelabuhan
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="info-card h-100">
                <div class="card-header-navy">
                    <i class="bi bi-funnel"></i> Filter & Pencarian
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="countryFilter">Cari Negara</label>
                        <select id="countryFilter" class="form-select">
                            <option value="">Semua negara</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="portSearch">Cari Pelabuhan</label>
                        <input id="portSearch" type="search" class="form-control" placeholder="Nama pelabuhan...">
                    </div>
                    <div class="small text-muted mb-2">
                        <span id="visibleCount">{{ $ports->count() }}</span> marker ditampilkan
                    </div>
                    <div id="portList" class="list-group list-group-flush" style="max-height: 420px; overflow-y: auto">
                        @foreach ($ports as $port)
                            <button type="button"
                                class="list-group-item list-group-item-action port-item"
                                data-id="{{ $port->id }}"
                                data-country-id="{{ $port->country_id }}"
                                data-search="{{ strtolower($port->name.' '.($port->country?->name ?? '')) }}">
                                <div class="fw-semibold">{{ $port->name }}</div>
                                <small class="text-muted">
                                    {{ $port->country?->name ?? '-' }}
                                    @if ($port->port_type)
                                        · {{ $port->port_type }}
                                    @endif
                                </small>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="info-card">
                <div class="card-body p-0">
                    <div id="portsMap" style="height: 680px"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
    .port-item.active { background: #e7f1ff; border-left: 3px solid #0d6efd; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const allPorts = @json($portMarkers);
    const map = L.map('portsMap').setView([0, 110], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const markers = new Map();

    allPorts.filter(p => p.lat && p.lng).forEach(port => {
        const marker = L.marker([port.lat, port.lng]).addTo(map);
        marker.bindPopup(`
            <strong>${port.name}</strong><br>
            ${port.country || '-'}<br>
            ${port.port_type ? 'Jenis: ' + port.port_type + '<br>' : ''}
            <a href="${port.url}">Detail pelabuhan</a>
        `);
        markers.set(String(port.id), marker);
    });

    const countryFilter = document.getElementById('countryFilter');
    const portSearch = document.getElementById('portSearch');
    const visibleCount = document.getElementById('visibleCount');
    const portItems = document.querySelectorAll('.port-item');

    function applyFilters() {
        const countryId = countryFilter.value;
        const keyword = portSearch.value.toLowerCase().trim();
        let shown = 0;

        portItems.forEach(item => {
            const matchCountry = !countryId || item.dataset.countryId === countryId;
            const matchKeyword = !keyword || item.dataset.search.includes(keyword);
            const visible = matchCountry && matchKeyword;
            item.classList.toggle('d-none', !visible);
            if (visible) shown++;
        });

        allPorts.forEach(port => {
            const marker = markers.get(String(port.id));
            if (!marker) return;
            const matchCountry = !countryId || String(port.country_id) === countryId;
            const matchKeyword = !keyword || `${port.name} ${port.country || ''}`.toLowerCase().includes(keyword);
            if (matchCountry && matchKeyword) {
                if (!map.hasLayer(marker)) marker.addTo(map);
            } else {
                map.removeLayer(marker);
            }
        });

        visibleCount.textContent = shown;
    }

    countryFilter.addEventListener('change', applyFilters);
    portSearch.addEventListener('input', applyFilters);

    portItems.forEach(item => {
        item.addEventListener('click', () => {
            const marker = markers.get(item.dataset.id);
            if (!marker) return;
            map.setView(marker.getLatLng(), 8);
            marker.openPopup();
            portItems.forEach(el => el.classList.remove('active'));
            item.classList.add('active');
        });
    });
</script>
@endpush
