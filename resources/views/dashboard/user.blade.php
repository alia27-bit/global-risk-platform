@extends('layouts.app')

@section('title', __('dashboard') . ' User')

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-section animate-in animate-in-1">
    <h2>
        {{ __('welcome') }}, {{ auth()->user()->nama }}! 👋
    </h2>
    <p>
        {{ __('logged_in_as') }}
        <span class="welcome-badge welcome-badge-user">
            <i class="bi bi-person-check-fill"></i> User
        </span>
        — {{ __('monitor_supply_chain') }}
    </p>
    <div class="welcome-time">
        <i class="bi bi-clock"></i>
        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y — H:i') }} WIB
    </div>
</div>

{{-- Kartu Statistik --}}
<div class="row mb-4">

    {{-- Negara Dipantau --}}
    <div class="col-md-4 mb-3">
        <div class="stat-card animate-in animate-in-2">
            <div class="card-body">
                <div class="stat-icon-box stat-icon-accent">
                    <i class="bi bi-binoculars"></i>
                </div>
                <div class="stat-label">{{ __('watched_countries') }}</div>
                <div class="stat-value">
                    {{ \App\Models\DaftarPantauan::where('user_id', auth()->id())->count() }}
                </div>
                <div class="stat-change stat-change-neutral">
                    <i class="bi bi-eye"></i> {{ __('in_your_watch') }}
                </div>
            </div>
            <i class="bi bi-binoculars stat-bg-icon"></i>
        </div>
    </div>

    {{-- Notifikasi --}}
    <div class="col-md-4 mb-3">
        <div class="stat-card animate-in animate-in-3">
            <div class="card-body">
                <div class="stat-icon-box stat-icon-warning">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <div class="stat-label">{{ __('notifications') }}</div>
                <div class="stat-value">
                    {{ \App\Models\Notifikasi::where('user_id', auth()->id())->count() }}
                </div>
                <div class="stat-change stat-change-neutral">
                    <i class="bi bi-envelope"></i> {{ __('incoming_messages') }}
                </div>
            </div>
            <i class="bi bi-bell-fill stat-bg-icon"></i>
        </div>
    </div>

    {{-- Negara Tersedia --}}
    <div class="col-md-4 mb-3">
        <div class="stat-card animate-in animate-in-4">
            <div class="card-body">
                <div class="stat-icon-box stat-icon-navy">
                    <i class="bi bi-globe2"></i>
                </div>
                <div class="stat-label">{{ __('available_countries') }}</div>
                <div class="stat-value">{{ \App\Models\Negara::count() }}</div>
                <div class="stat-change stat-change-up">
                    <i class="bi bi-database"></i> {{ __('can_be_watched') }}
                </div>
            </div>
            <i class="bi bi-globe2 stat-bg-icon"></i>
        </div>
    </div>

</div>

<div class="row">

    {{-- Panel Akses Cepat User --}}
    <div class="col-md-7 mb-4">
        <div class="info-card animate-slide animate-slide-1">
            <div class="card-header-navy">
                <i class="bi bi-lightning-charge-fill"></i>
                {{ __('quick_access') }} — {{ __('monitoring_menu') }}
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="#" class="quick-action">
                            <div class="qa-icon stat-icon-accent">
                                <i class="bi bi-binoculars"></i>
                            </div>
                            <div>
                                <div class="qa-label">{{ __('watchlist_label') }}</div>
                                <div class="qa-desc">{{ __('watchlist_desc') }}</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="#" class="quick-action">
                            <div class="qa-icon stat-icon-navy">
                                <i class="bi bi-globe2"></i>
                            </div>
                            <div>
                                <div class="qa-label">{{ __('view_countries_label') }}</div>
                                <div class="qa-desc">{{ __('view_countries_desc') }}</div>
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
                                <div class="qa-desc">{{ __('risk_level_current') }}</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="#" class="quick-action">
                            <div class="qa-icon stat-icon-warning">
                                <i class="bi bi-bell-fill"></i>
                            </div>
                            <div>
                                <div class="qa-label">{{ __('notifications_label') }}</div>
                                <div class="qa-desc">{{ __('notifications_desc') }}</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Panel Fitur User --}}
    <div class="col-md-5 mb-4">
        <div class="info-card animate-slide animate-slide-2">
            <div class="card-header-navy">
                <i class="bi bi-info-circle-fill"></i>
                {{ __('available_features') }}
            </div>
            <div class="card-body">
                <p class="mb-2" style="font-size: 0.82rem;">{{ __('user_access_intro') }}</p>
                <ul class="feature-list">
                    <li>
                        <span class="feature-icon feature-icon-accent"><i class="bi bi-eye"></i></span>
                        {{ __('user_feat_1') }}
                    </li>
                    <li>
                        <span class="feature-icon feature-icon-accent"><i class="bi bi-pin-map"></i></span>
                        {{ __('user_feat_2') }}
                    </li>
                    <li>
                        <span class="feature-icon feature-icon-navy"><i class="bi bi-shield-check"></i></span>
                        {{ __('user_feat_3') }}
                    </li>
                    <li>
                        <span class="feature-icon feature-icon-navy"><i class="bi bi-newspaper"></i></span>
                        {{ __('user_feat_4') }}
                    </li>
                    <li>
                        <span class="feature-icon feature-icon-accent"><i class="bi bi-bell"></i></span>
                        {{ __('user_feat_5') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>

@endsection
