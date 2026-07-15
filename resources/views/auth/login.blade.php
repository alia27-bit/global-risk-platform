<x-guest-layout>

    <h2>{{ __('messages.login_title') }}</h2>
    <p class="auth-subtitle">{{ __('messages.login_subtitle') }}</p>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="auth-status">
            {{ session('status') }}
        </div>
    @endif

    {{-- Language Switcher --}}
    <div style="display: flex; justify-content: center; gap: 0.5rem; margin-bottom: 1.5rem;">
        <a href="{{ route('language.switch', 'id') }}"
           style="display:inline-flex;align-items:center;gap:.3rem;padding:.3rem .7rem;border-radius:6px;font-size:.75rem;font-weight:500;text-decoration:none;
           {{ app()->getLocale() === 'id' ? 'background:#1b2a4a;color:#fff;' : 'background:#f1f3f6;color:#6b7385;border:1px solid #e2e6ed;' }}">
            🇮🇩 Indonesia
        </a>

        <a href="{{ route('language.switch', 'en') }}"
           style="display:inline-flex;align-items:center;gap:.3rem;padding:.3rem .7rem;border-radius:6px;font-size:.75rem;font-weight:500;text-decoration:none;
           {{ app()->getLocale() === 'en' ? 'background:#1b2a4a;color:#fff;' : 'background:#f1f3f6;color:#6b7385;border:1px solid #e2e6ed;' }}">
            🇬🇧 English
        </a>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="form-group">
            <label for="email">{{ __('messages.email') }}</label>

            <div class="input-wrapper">
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="{{ __('messages.email_placeholder') }}"
                    required
                    autofocus
                    autocomplete="username">

                <i class="bi bi-envelope input-icon"></i>
            </div>

            @error('email')
                <p class="input-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="form-group">
            <label for="password">{{ __('messages.password') }}</label>

            <div class="input-wrapper">
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="{{ __('messages.password_placeholder') }}"
                    required
                    autocomplete="current-password">

                <i class="bi bi-lock input-icon"></i>
            </div>

            @error('password')
                <p class="input-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="form-check">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me">
                {{ __('messages.remember_me') }}
            </label>
        </div>

        {{-- Login Button --}}
        <button type="submit" class="btn-auth-primary">
            <i class="bi bi-box-arrow-in-right"></i>
            {{ __('messages.login_button') }}
        </button>

        {{-- Links --}}
        <div class="auth-links">

            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">
                    {{ __('messages.forgot_password') }}
                </a>
            @endif

            <a class="auth-link" href="{{ route('register') }}">
                {{ __('messages.no_account') }}
                <strong>{{ __('messages.register_link') }}</strong>
            </a>

        </div>

    </form>

</x-guest-layout>