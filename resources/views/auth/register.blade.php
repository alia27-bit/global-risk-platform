<x-guest-layout>

    <h2>{{ __('register_title') }}</h2>
    <p class="auth-subtitle">{{ __('register_subtitle') }}</p>

    {{-- Language Switcher di halaman register --}}
    <div style="display: flex; justify-content: center; gap: 0.5rem; margin-bottom: 1.5rem;">
        <a href="{{ route('language.switch', 'id') }}"
           style="display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.3rem 0.7rem; border-radius: 6px; font-size: 0.75rem; font-weight: 500; text-decoration: none; transition: all 0.2s;
           {{ app()->getLocale() === 'id' ? 'background: #1b2a4a; color: #fff;' : 'background: #f1f3f6; color: #6b7385; border: 1px solid #e2e6ed;' }}">
            🇮🇩 Indonesia
        </a>
        <a href="{{ route('language.switch', 'en') }}"
           style="display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.3rem 0.7rem; border-radius: 6px; font-size: 0.75rem; font-weight: 500; text-decoration: none; transition: all 0.2s;
           {{ app()->getLocale() === 'en' ? 'background: #1b2a4a; color: #fff;' : 'background: #f1f3f6; color: #6b7385; border: 1px solid #e2e6ed;' }}">
            🇬🇧 English
        </a>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nama --}}
        <div class="form-group">
            <label for="name">{{ __('full_name') }}</label>
            <div class="input-wrapper">
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="{{ __('full_name_placeholder') }}"
                    required
                    autofocus
                    autocomplete="name"
                >
                <i class="bi bi-person input-icon"></i>
            </div>
            @error('name')
                <p class="input-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label for="email">{{ __('email') }}</label>
            <div class="input-wrapper">
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="{{ __('email_placeholder') }}"
                    required
                    autocomplete="username"
                >
                <i class="bi bi-envelope input-icon"></i>
            </div>
            @error('email')
                <p class="input-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="form-group">
            <label for="password">{{ __('password') }}</label>
            <div class="input-wrapper">
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="{{ __('password_min') }}"
                    required
                    autocomplete="new-password"
                >
                <i class="bi bi-lock input-icon"></i>
            </div>
            @error('password')
                <p class="input-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="form-group">
            <label for="password_confirmation">{{ __('confirm_password') }}</label>
            <div class="input-wrapper">
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    placeholder="{{ __('confirm_password_placeholder') }}"
                    required
                    autocomplete="new-password"
                >
                <i class="bi bi-shield-lock input-icon"></i>
            </div>
            @error('password_confirmation')
                <p class="input-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-auth-primary">
            <i class="bi bi-person-plus"></i>
            {{ __('register_button') }}
        </button>

        {{-- Link ke Login --}}
        <div class="auth-links" style="justify-content: center;">
            <a class="auth-link" href="{{ route('login') }}">
                {{ __('has_account') }} <strong>{{ __('login_link') }}</strong>
            </a>
        </div>
    </form>

</x-guest-layout>
