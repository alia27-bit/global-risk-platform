<div class="col-md-2 sidebar">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>{{ __('messages.dashboard') }}
            </a>
        </li>

        <li class="nav-item mt-3">
            <span class="nav-section-title">
                {{ auth()->user()->peran === 'admin' ? __('messages.data_management') : __('messages.personal_monitoring') }}
            </span>
        </li>

        @if(auth()->user()->peran === 'user')
            <li class="nav-item"><a href="{{ route('favorite-monitoring.index') }}" class="nav-link {{ request()->routeIs('favorite-monitoring.*') || request()->routeIs('watchlist.*') ? 'active' : '' }}"><i class="bi bi-star-fill"></i>{{ __('messages.favorite_monitoring') }}</a></li>
        @endif

        <li class="nav-item"><a href="{{ route('countries.index') }}" class="nav-link {{ request()->routeIs('countries.*') ? 'active' : '' }}"><i class="bi bi-globe2"></i>{{ __('messages.country') }}</a></li>
        <li class="nav-item"><a href="{{ route('weather.map') }}" class="nav-link {{ request()->routeIs('weather.*') ? 'active' : '' }}"><i class="bi bi-cloud-sun"></i>{{ __('messages.weather_map') }}</a></li>
        <li class="nav-item"><a href="{{ route('economy.index') }}" class="nav-link {{ request()->routeIs('economy.*') ? 'active' : '' }}"><i class="bi bi-graph-up-arrow"></i>{{ __('messages.economic_indicator') }}</a></li>
        <li class="nav-item"><a href="{{ route('exchange.index') }}" class="nav-link {{ request()->routeIs('exchange.*') ? 'active' : '' }}"><i class="bi bi-currency-exchange"></i>{{ __('messages.exchange_rate') }}</a></li>
        <li class="nav-item"><a href="{{ route('ports.map') }}" class="nav-link {{ request()->routeIs('ports.map') ? 'active' : '' }}"><i class="bi bi-map"></i>{{ __('messages.port_map') }}</a></li>
        <li class="nav-item"><a href="{{ route('ports.index') }}" class="nav-link {{ request()->routeIs('ports.*') && ! request()->routeIs('ports.map') ? 'active' : '' }}"><i class="bi bi-geo-alt-fill"></i>{{ __('messages.port') }}</a></li>
        <li class="nav-item"><a href="{{ route('news.index') }}" class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}"><i class="bi bi-newspaper"></i>{{ __('messages.news') }}</a></li>
        <li class="nav-item"><a href="{{ route('articles.public.index') }}" class="nav-link {{ request()->routeIs('articles.public.*') ? 'active' : '' }}"><i class="bi bi-journal-text"></i>{{ __('messages.analysis_articles') }}</a></li>

        <li class="nav-item mt-3"><span class="nav-section-title">{{ __('messages.analysis') }}</span></li>
        <li class="nav-item"><a href="{{ route('risk.index') }}" class="nav-link {{ request()->routeIs('risk.*') ? 'active' : '' }}"><i class="bi bi-shield-exclamation"></i>{{ __('messages.risk_score') }}</a></li>
        <li class="nav-item"><a href="{{ route('comparison.index') }}" class="nav-link {{ request()->routeIs('comparison.*') ? 'active' : '' }}"><i class="bi bi-columns-gap"></i>{{ __('messages.comparison') }}</a></li>

        @if(auth()->user()->peran === 'admin')
            <li class="nav-item mt-3"><span class="nav-section-title">{{ __('messages.administration') }}</span></li>
            <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="bi bi-people"></i>{{ __('messages.users') }}</a></li>
            <li class="nav-item"><a href="{{ route('admin.articles.index') }}" class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}"><i class="bi bi-journal-richtext"></i>{{ __('messages.analysis_articles') }}</a></li>
        @endif

        <li class="nav-item mt-3"><span class="nav-section-title">{{ __('messages.account') }}</span></li>
        <li class="nav-item"><a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"><i class="bi bi-person-circle"></i>{{ __('messages.profile') }}</a></li>
    </ul>
</div>
