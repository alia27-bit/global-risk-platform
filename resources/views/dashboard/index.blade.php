@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

        <h2>
            <i class="bi bi-globe-americas"></i>
            {{ __('messages.welcome') }}, {{ auth()->user()->nama }}    <div class="welcome-section animate-in animate-in-1">
            <span class="welcome-badge {{ auth()->user()->peran === 'admin' ? 'welcome-badge-admin' : 'welcome-badge-user' }}">
                <i class="bi bi-{{ auth()->user()->peran === 'admin' ? 'shield-lock' : 'person' }}"></i>
                {{ ucfirst(auth()->user()->peran) }}
            </span>
        </h2>
<p>{{ __('messages.monitor_supply_chain') }}</p>        <div class="welcome-time">
            <i class="bi bi-clock"></i>
            {{ now()->translatedFormat('l, d F Y — H:i') }} WIB
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-4 mb-4">

        <div class="col-md-3 animate-in animate-in-2">
            <div class="stat-card">
                <div class="card-body">
                    <div class="stat-icon-box stat-icon-navy">
                        <i class="bi bi-globe"></i>
                    </div>
                    <div class="stat-label">Total Negara</div>
                    <div class="stat-value">{{ $totalCountries }}</div>
                </div>
                <i class="bi bi-globe stat-bg-icon"></i>
            </div>
        </div>

        <div class="col-md-3 animate-in animate-in-3">
            <div class="stat-card">
                <div class="card-body">
                    <div class="stat-icon-box stat-icon-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="stat-label">Risiko Tinggi</div>
                    <div class="stat-value">{{ $highRisk }}</div>
                </div>
                <i class="bi bi-exclamation-triangle stat-bg-icon"></i>
            </div>
        </div>

        <div class="col-md-3 animate-in animate-in-4">
            <div class="stat-card">
                <div class="card-body">
                    <div class="stat-icon-box stat-icon-warning">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>
                    <div class="stat-label">Risiko Sedang</div>
                    <div class="stat-value">{{ $mediumRisk }}</div>
                </div>
                <i class="bi bi-shield-exclamation stat-bg-icon"></i>
            </div>
        </div>

        <div class="col-md-3 animate-in animate-in-5">
            <div class="stat-card">
                <div class="card-body">
                    <div class="stat-icon-box stat-icon-success">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="stat-label">Risiko Rendah</div>
                    <div class="stat-value">{{ $lowRisk }}</div>
                </div>
                <i class="bi bi-shield-check stat-bg-icon"></i>
            </div>
        </div>

    </div>

    <div class="row g-4">

        {{-- Top Risk Countries --}}
        <div class="col-12 col-lg-8">
            <div class="info-card">
                <div class="card-header-navy">
                    <i class="bi bi-bar-chart-fill"></i>
                    Top 10 Negara Berisiko Tertinggi
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Negara</th>
                                    <th>Skor</th>
                                    <th>Kategori</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topRisks as $risk)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($risk->country)
<img src="{{ $risk->country->flag }}"
     width="24"
     class="img-fluid">                                                {{ $risk->country->name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td><strong>{{ $risk->total_score }}</strong></td>
                                        <td>
                                            @php
                                                $badgeClass = match($risk->category) {
                                                    'High' => 'bg-danger',
                                                    'Medium' => 'bg-warning text-dark',
                                                    'Low' => 'bg-success',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $risk->category }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            Belum ada data risk score. Klik "Hitung Risk" pada halaman Negara.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Latest News --}}
        <div class="col-12 col-lg-4">
            <div class="info-card">
                <div class="card-header-navy">
                    <i class="bi bi-newspaper"></i>
                    Berita Terbaru
                </div>
                <div class="card-body">
                    @forelse($news as $item)
                        <div class="d-flex align-items-start gap-2 mb-3 pb-3 border-bottom">
                            <div>
                                <div class="fw-semibold" style="font-size: 0.85rem;">{{ Str::limit($item->title, 60) }}</div>
                                <small class="text-muted">
                                    {{ $item->source ?? 'Unknown' }}
                                    · {{ $item->published_at ? $item->published_at->diffForHumans() : '' }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3 mb-0">
                            Belum ada berita. Sync dari halaman Negara.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-12 mt-4">
    <div class="info-card">

        <div class="card-header-navy">

            <i class="bi bi-clock-history"></i>

            API Logs

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>API</th>

                            <th>Status</th>

                            <th>Message</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($apiLogs as $log)

                        <tr>

                            <td>{{ $log->api_name }}</td>

                            <td>{{ $log->status_code }}</td>

                            <td>{{ $log->message }}</td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="3" class="text-center">

                                No API logs.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

    {{-- Quick Access --}}
    <div class="row g-3 mt-3">
        <div class="col-12">
            <h6 class="text-muted mb-3">
                <i class="bi bi-lightning-charge"></i> Akses Cepat
            </h6>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('countries.index') }}" class="quick-action">
                <div class="qa-icon stat-icon-navy">
                    <i class="bi bi-globe"></i>
                </div>
                <div>
                    <div class="qa-label">Data Negara</div>
                    <div class="qa-desc">Kelola data negara</div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('countries.sync') }}" class="quick-action">
                <div class="qa-icon stat-icon-accent">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div>
                    <div class="qa-label">Sync API</div>
                    <div class="qa-desc">Sinkronisasi data negara</div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('news.index') }}" class="quick-action">
                <div class="qa-icon stat-icon-warning">
                    <i class="bi bi-newspaper"></i>
                </div>
                <div>
                    <div class="qa-label">Berita</div>
                    <div class="qa-desc">Berita rantai pasok</div>
                </div>
            </a>
        </div>
       <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('ports.index') }}" class="quick-action">
                <div class="qa-icon stat-icon-info">
                    <i class="bi bi-signpost-split"></i>
                </div>
                <div>
                    <div class="qa-label">Pelabuhan</div>
                    <div class="qa-desc">Data pelabuhan internasional</div>
                </div>
            </a>
        </div>
    </div>

</div>

@endsection