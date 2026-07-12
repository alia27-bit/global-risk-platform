<div class="col-md-2 sidebar">

    <ul class="nav flex-column">

        {{-- Dashboard (semua peran) --}}
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                {{ __('dashboard') }}
            </a>
        </li>

        @if(auth()->user()->peran == 'admin')

            {{-- ========== MENU ADMIN ========== --}}

            <li class="nav-item">
                <span class="nav-section-title">{{ __('master_data') }}</span>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-globe2"></i>
                    {{ __('country') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-cloud-sun"></i>
                    {{ __('weather_data') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-graph-up-arrow"></i>
                    {{ __('economic_indicator') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-currency-exchange"></i>
                    {{ __('exchange_rate') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-geo-alt-fill"></i>
                    {{ __('port') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-newspaper"></i>
                    {{ __('news') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-journal-richtext"></i>
                    {{ __('article') }}
                </a>
            </li>

            <li class="nav-item">
                <span class="nav-section-title">{{ __('analysis') }}</span>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-chat-square-text"></i>
                    {{ __('sentiment_analysis') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-shield-exclamation"></i>
                    {{ __('risk_score') }}
                </a>
            </li>

            <li class="nav-item">
                <span class="nav-section-title">{{ __('system') }}</span>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-people-fill"></i>
                    {{ __('user_management') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-gear"></i>
                    {{ __('settings') }}
                </a>
            </li>

        @else

            {{-- ========== MENU USER ========== --}}

            <li class="nav-item">
                <span class="nav-section-title">{{ __('monitoring') }}</span>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-binoculars"></i>
                    {{ __('watchlist') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-globe2"></i>
                    {{ __('view_countries') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-shield-check"></i>
                    {{ __('risk_score') }}
                </a>
            </li>

            <li class="nav-item">
                <span class="nav-section-title">{{ __('information') }}</span>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-newspaper"></i>
                    {{ __('news') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-bell"></i>
                    {{ __('notifications') }}
                </a>
            </li>

        @endif

    </ul>

</div>