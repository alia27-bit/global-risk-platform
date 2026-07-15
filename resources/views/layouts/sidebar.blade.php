<div class="col-md-2 sidebar">

    <ul class="nav flex-column">

        {{-- ================= DASHBOARD ================= --}}
        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">

                <i class="bi bi-speedometer2"></i>

                {{ __('messages.dashboard') }}

            </a>
        </li>

        @if(auth()->user()->peran == 'admin')

            {{-- ========================================= --}}
            {{-- MASTER DATA                              --}}
            {{-- ========================================= --}}

            <li class="nav-item mt-3">
                <span class="nav-section-title">
                    {{ __('messages.master_data') }}
                </span>
            </li>

            <li class="nav-item">
                <a href="{{ route('countries.index') }}"
                   class="nav-link {{ request()->routeIs('countries.*') ? 'active' : '' }}">

                    <i class="bi bi-globe2"></i>

                    {{ __('messages.country') }}

                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('countries.index') }}"
                   class="nav-link">

                    <i class="bi bi-cloud-sun"></i>

                    {{ __('messages.weather_data') }}

                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('countries.index') }}"
                   class="nav-link">

                    <i class="bi bi-graph-up-arrow"></i>

                    {{ __('messages.economic_indicator') }}

                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('countries.index') }}"
                   class="nav-link">

                    <i class="bi bi-currency-exchange"></i>

                    {{ __('messages.exchange_rate') }}

                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('ports.index') }}"
                   class="nav-link {{ request()->routeIs('ports.*') ? 'active' : '' }}">

                    <i class="bi bi-geo-alt-fill"></i>

                    {{ __('messages.port') }}

                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('news.index') }}"
                   class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}">

                    <i class="bi bi-newspaper"></i>

                    {{ __('messages.news') }}

                </a>
            </li>

            {{-- ========================================= --}}
            {{-- ANALYSIS                                 --}}
            {{-- ========================================= --}}

            <li class="nav-item mt-3">
                <span class="nav-section-title">

                    {{ __('messages.analysis') }}

                </span>
            </li>

            <li class="nav-item">
                <a href="{{ route('risk.index') }}"
                   class="nav-link {{ request()->routeIs('risk.*') ? 'active' : '' }}">

                    <i class="bi bi-shield-exclamation"></i>

                    {{ __('messages.risk_score') }}

                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('comparison.index') }}"
                   class="nav-link {{ request()->routeIs('comparison.*') ? 'active' : '' }}">

                    <i class="bi bi-columns-gap"></i>

                    {{ __('messages.comparison') }}

                </a>
            </li>

            {{-- ========================================= --}}
            {{-- SYSTEM                                   --}}
            {{-- ========================================= --}}

            <li class="nav-item mt-3">
                <span class="nav-section-title">

                    {{ __('messages.system') }}

                </span>
            </li>

            <li class="nav-item">
                <a href="{{ route('profile.edit') }}"
                   class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">

                    <i class="bi bi-person-circle"></i>

                    {{ __('messages.profile') }}

                </a>
            </li>

        @else

            {{-- ========================================= --}}
            {{-- USER MENU                                --}}
            {{-- ========================================= --}}

            <li class="nav-item mt-3">

                <span class="nav-section-title">

                    {{ __('messages.monitoring') }}

                </span>

            </li>

            <li class="nav-item">
                <a href="{{ route('watchlist.index') }}"
                   class="nav-link {{ request()->routeIs('watchlist.*') ? 'active' : '' }}">

                    <i class="bi bi-binoculars"></i>

                    {{ __('messages.watchlist') }}

                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('countries.index') }}"
                   class="nav-link {{ request()->routeIs('countries.*') ? 'active' : '' }}">

                    <i class="bi bi-globe2"></i>

                    {{ __('messages.view_countries') }}

                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('risk.index') }}"
                   class="nav-link {{ request()->routeIs('risk.*') ? 'active' : '' }}">

                    <i class="bi bi-shield-check"></i>

                    {{ __('messages.risk_score') }}

                </a>
            </li>

            <li class="nav-item mt-3">

                <span class="nav-section-title">

                    {{ __('messages.information') }}

                </span>

            </li>

            <li class="nav-item">
                <a href="{{ route('news.index') }}"
                   class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}">

                    <i class="bi bi-newspaper"></i>

                    {{ __('messages.news') }}

                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('comparison.index') }}"
                   class="nav-link {{ request()->routeIs('comparison.*') ? 'active' : '' }}">

                    <i class="bi bi-columns-gap"></i>

                    {{ __('messages.comparison') }}

                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('profile.edit') }}"
                   class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">

                    <i class="bi bi-person-circle"></i>

                    {{ __('messages.profile') }}

                </a>
            </li>

        @endif

    </ul>

</div>