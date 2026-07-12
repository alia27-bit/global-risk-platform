@extends('layouts.app')

@section('title', __('dashboard') . ' Admin')

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-section animate-in animate-in-1">
    <h2>
        {{ __('welcome') }}, {{ auth()->user()->nama }}! 👋
    </h2>
    <p>
        {{ __('logged_in_as') }}
        <span class="welcome-badge welcome-badge-admin">
            <i class="bi bi-star-fill"></i> {{ __('administrator') }}
        </span>
        — {{ __('manage_platform') }}
    </p>
    <div class="welcome-time">
        <i class="bi bi-clock"></i>
        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y — H:i') }} WIB
    </div>
</div>

{{-- Kartu Statistik --}}
<div class="row mb-4">

    {{-- Jumlah Negara --}}
    <div class="col-md-3 mb-3">
        <div class="stat-card animate-in animate-in-2">
            <div class="card-body">
                <div class="stat-icon-box stat-icon-navy">
                    <i class="bi bi-globe2"></i>
                </div>
                <div class="stat-label">{{ __('total_countries') }}</div>
                <div class="stat-value">{{ \App\Models\Negara::count() }}</div>
                <div class="stat-change stat-change-neutral">
                    <i class="bi bi-database"></i> {{ __('data_stored') }}
                </div>
            </div>
            <i class="bi bi-globe2 stat-bg-icon"></i>
        </div>
    </div>

    {{-- Jumlah Pelabuhan --}}
    <div class="col-md-3 mb-3">
        <div class="stat-card animate-in animate-in-3">
            <div class="card-body">
                <div class="stat-icon-box stat-icon-success">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <div class="stat-label">{{ __('total_ports') }}</div>
                <div class="stat-value">{{ \App\Models\Pelabuhan::count() }}</div>
                <div class="stat-change stat-change-neutral">
                    <i class="bi bi-database"></i> {{ __('data_stored') }}
                </div>
            </div>
            <i class="bi bi-geo-alt-fill stat-bg-icon"></i>
        </div>
    </div>

    {{-- Jumlah Berita --}}
    <div class="col-md-3 mb-3">
        <div class="stat-card animate-in animate-in-4">
            <div class="card-body">
                <div class="stat-icon-box stat-icon-warning">
                    <i class="bi bi-newspaper"></i>
                </div>
                <div class="stat-label">{{ __('total_news') }}</div>
                <div class="stat-value">{{ \App\Models\Berita::count() }}</div>
                <div class="stat-change stat-change-neutral">
                    <i class="bi bi-database"></i> {{ __('data_stored') }}
                </div>
            </div>
            <i class="bi bi-newspaper stat-bg-icon"></i>
        </div>
    </div>

    {{-- Jumlah User --}}
    <div class="col-md-3 mb-3">
        <div class="stat-card animate-in animate-in-5">
            <div class="card-body">
                <div class="stat-icon-box stat-icon-accent">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-label">{{ __('total_users') }}</div>
                <div class="stat-value">{{ \App\Models\User::count() }}</div>
                <div class="stat-change stat-change-up">
                    <i class="bi bi-person-check"></i> {{ __('registered') }}
                </div>
            </div>
            <i class="bi bi-people-fill stat-bg-icon"></i>
        </div>
    </div>

</div>

<div class="row">

    {{-- Panel Akses Cepat --}}
    <div class="col-md-7 mb-4">
        <div class="info-card animate-slide animate-slide-1">
            <div class="card-header-navy">
                <i class="bi bi-lightning-charge-fill"></i>
                {{ __('quick_access') }} — {{ __('admin_menu') }}
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="#" class="quick-action">
                            <div class="qa-icon stat-icon-navy">
                                <i class="bi bi-globe2"></i>
                            </div>
                            <div>
                                <div class="qa-label">{{ __('manage_countries') }}</div>
                                <div class="qa-desc">{{ __('manage_countries_desc') }}</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="#" class="quick-action">
                            <div class="qa-icon stat-icon-success">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <div class="qa-label">{{ __('manage_ports') }}</div>
                                <div class="qa-desc">{{ __('manage_ports_desc') }}</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="#" class="quick-action">
                            <div class="qa-icon stat-icon-warning">
                                <i class="bi bi-newspaper"></i>
                            </div>
                            <div>
                                <div class="qa-label">{{ __('manage_news') }}</div>
                                <div class="qa-desc">{{ __('manage_news_desc') }}</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="#" class="quick-action">
                            <div class="qa-icon stat-icon-danger">
                                <i class="bi bi-shield-exclamation"></i>
                            </div>
                            <div>
                                <div class="qa-label">{{ __('risk_score_label') }}</div>
                                <div class="qa-desc">{{ __('risk_score_desc') }}</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Panel Info Hak Akses --}}
    <div class="col-md-5 mb-4">
        <div class="info-card animate-slide animate-slide-2">
            <div class="card-header-navy">
                <i class="bi bi-shield-lock-fill"></i>
                {{ __('admin_access_title') }}
            </div>
            <div class="card-body">
                <p class="mb-2" style="font-size: 0.82rem;">{{ __('admin_access_intro') }}</p>
                <ul class="feature-list">
                    <li>
                        <span class="feature-icon feature-icon-navy"><i class="bi bi-pencil-square"></i></span>
                        {{ __('admin_feat_1') }}
                    </li>
                    <li>
                        <span class="feature-icon feature-icon-navy"><i class="bi bi-journal-richtext"></i></span>
                        {{ __('admin_feat_2') }}
                    </li>
                    <li>
                        <span class="feature-icon feature-icon-accent"><i class="bi bi-bar-chart-line"></i></span>
                        {{ __('admin_feat_3') }}
                    </li>
                    <li>
                        <span class="feature-icon feature-icon-accent"><i class="bi bi-people"></i></span>
                        {{ __('admin_feat_4') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>

@endsection
